<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanSubscriptionRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Interfaces\PatientInterface;
use App\Interfaces\PaymentInterface;
use App\Models\Discount;
use App\Models\Patient;
use App\Models\Plan;
use App\Models\Subscriber;
use App\Models\Transaction;
use App\Services\PlanService;

date_default_timezone_set('Africa/Cairo');


class ReciptionController extends Controller
{
    protected $patientService;
    protected $paymentService;
    protected $planService;
    public function __construct(PatientInterface $patientService,
    PaymentInterface $paymentService,
    PlanService $planService
    )
    {
        $this->patientService = $patientService;
        $this->paymentService = $paymentService;
        $this->planService =$planService;
    }
    public function index()
    {

        $clinics = Clinic::all();
        return view('reciption.index', compact('clinics'));
    }
    public  function search(Request $request)
    {

        $request->validate([
            'name' => "required"
        ]);
        $clinics = Clinic::where('name', 'like', '%' . $request->name . '%')->get();


        return response()->json($clinics);
    }
    public function getAllClinics()
    {
        $clinics = Clinic::all();
        return response()->json($clinics);
    }
    public function showClinic($id)
    {
        $clinic = Clinic::findOrFail($id)->load('doctors');
        return view('reciption.show', compact('clinic'));
    }
    public function timeTable($id)
    {
        $doctor = Doctor::findOrFail($id);
        if(!$doctor->timeTable){
            abort(404);
        }
        return view('reciption.timeTable', ['timeTable' => $doctor->timeTable]);
    }
    public function registerForm($id)
    {
        $doctor = Doctor::findOrFail($id);
        if(!$doctor->timeTable){
            abort(404);
        }
        $times = TimesWhereNotNull($doctor);


        $AllDays = ['sat' => 'Saturday', 'sun' => 'Sunday', 'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thurs' => 'Thursday', 'fri' => 'Friday'];
        $days = [];
        foreach ($times as $key => $date) {
            $times[$key] = Carbon::createFromFormat('H:i:s', $date)->format('h:i A');
        }

        foreach ($times as $key => $val) {
            $subString = substr($key, 0, 3); //sun
            if ($subString == 'thu') {
                $subString = "thurs";
            }

            if (!array_key_exists($subString, $days)) {
                $days[$subString] = $AllDays[$subString];
            }
        }

        
        return view('reciption.register', compact('doctor', 'times', 'days'));
    }
    // create Appointment 
    public function createAppointment(Request $request, $id)
    {   
        
        $doctor =Doctor::findOrFail($id);
        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'phone' => ['required', 'max:13'],
                'date' => ['required'],
                'type' => ['required', Rule::in(['examination', 'consultation'])],
                'age' => ['required'],
                'gender' => ['required', Rule::in(['male', 'female'])],
                'payment_method' => ['required', Rule::in(['cash', 'online'])],
                'national_id' => ['nullable','max:50'],
                'paid' => 'nullable',
                
            ]
        );
       
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        if(substr($request->day,0,3) !== date_format(date_create($request->date),'D')){
            return redirect()->back()->withErrors(['date'=>'date must match day'])->withInput();
        }

       
        $registration_method = 'reception';
        if($request->payment_method == 'cash'){

            $this->patientService->register_Cash($validator->validated(),$doctor,$registration_method);
            return redirect()->back()->with('success', 'Appointment registered successfully!');
        }else{

          $appointment=  $this->patientService->register_Online($validator->validated(),$doctor,$registration_method);
            return  $this->paymentService->pay($appointment);
        }

    
    }
    // appointments view
    public function appointments($id,$current = null,$week="this")  {
        $doctor = Doctor::findOrFail($id);
        $days=GetDaysNames(TimesWhereNotNull($doctor));
        if($current == null){
            $current =GetCurrentDay($days);
        }
        $today = new \DateTime("$week $current");
        $date=$today->format("Y-m-d");
        if($week == "next"){
            $today->modify("+1 week");
        }
        $appointments=$doctor->appointments->where('date',date($date))->where('status','pending');
        return view('reciption.appointments',compact('doctor','days','current','appointments','week','date'));
    }
    // show appointment
    public function appointment($id,$from=null)  {
        
        $appointment = Appointment::findOrFail($id)->load(['patient','doctor']);
        $times = TimesWhereNotNull($appointment->doctor);


        $AllDays = ['sat' => 'Saturday', 'sun' => 'Sunday', 'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thurs' => 'Thursday', 'fri' => 'Friday'];
        $days = [];
        foreach ($times as $key => $date) {
            $times[$key] = Carbon::createFromFormat('H:i:s', $date)->format('h:i A');
        }

        foreach ($times as $key => $val) {
            $subString = substr($key, 0, 3); //sun
            if ($subString == 'thu') {
                $subString = "thurs";
            }

            if (!array_key_exists($subString, $days)) {
                $days[$subString] = $AllDays[$subString];
            }
        }
        return view('reciption.appointment',compact('appointment','times', 'days','from'));
    }
    // update Appointment
    public function updateAppointment(Request $request , $id)  {
              
        $appointment =Appointment::findOrFail($id);
        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'phone' => ['required', 'max:13'],
                'date' => ['required'],
                'type' => ['required', Rule::in(['examination', 'consultation'])],
                'age' => ['required'],
                'gender' => ['required', Rule::in(['male', 'female'])],
                'payment_method' => ['required', Rule::in(['cash', 'online'])],
                'national_id' => ['nullable','max:50'],
                'paid' => 'nullable',
                
            ]
        );
        // dd($request->all());
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        if(substr($request->day,0,3) !== date_format(date_create($request->date),'D')){
            return redirect()->back()->withErrors(['date'=>'date must match day'])->withInput();
        } 
        $this->patientService->updateAppointment($appointment,$validator->validated());
        return redirect()->back()->with('success', 'Appointment updated successfully!');

    }
    public function deleteAppointment($id)  {
            $appointment =Appointment::findOrFail($id);
            $doctor=$appointment->doctor;
            $appointment->delete();
            return to_route('reception.appointments',$doctor->id);
    }
    //view old appointments
    public function archive($id,$current=null){
        $doctor =Doctor::findOrFail($id);
        $days=GetDaysNames(TimesWhereNotNull($doctor));
        if($current == null){
            $current =GetCurrentDay($days);
        }
        $appointments =$this->patientService->archive($doctor,$current);
        return view('reciption.archive',compact('doctor','appointments','days','current'));
    }
    // transactions
    public function transactions($id){
        $doctor =Doctor::findOrFail($id);
        $appointments =Appointment::where('doctor_id',$id)->where('payment_method','online')->with('transaction')->where('paid',true)->get()->sortByDesc('date');
        // dd($appointments);
        return view('reciption.transactions',compact('appointments','doctor'));
    }
    public function transaction_delete($id)  {
        $transaction =Transaction::findOrFail($id);
        $appointment= $transaction->appointment;
        $appointment->paid = false;
        $appointment->save();
        $transaction->delete();
        return redirect()->back()->with('success','transaction deleted successfully');
    }
    // has discount
    public function hasDiscount(Request $request) {
        $national_id =$request->national_id;
        $doctor_id=$request->doctor_id;
        $patient =Patient::where('national_id',$national_id)->first();
        $doctor =Doctor::findOrFail($doctor_id);
        if($patient){
        $appointments = count($patient->appointments->where('doctor_id',$doctor->id));
        $discounts =Discount::all();
        $patient_discount=0;
        $discount_name="";
        foreach ($discounts as $discount) {
            if (str_contains($discount->name,'Appointments')) {
                $number = (int) explode('-',$discount->name)[0];
                if($appointments >= $number){
                    $patient_discount=$discount->discount;
                    $discount_name =$discount->name;
                }
            }
        }
        if ($patient->donation) {
            $patient_discount=Discount::where('name','donator')->first()->discount;
            $discount_name ='Donator';
        }
        if ($patient_discount) {
            $price = $doctor->price - ($patient_discount*$doctor->price);
            $data=[
                'status'=>'found',
                'message'=>"this patient has a $discount_name discount",
                'price'=>$price
            ];
            return response()->json($data,200);
        }
        return response()->json(['status'=>'not-found',],200);

        }else{
        return response()->json(['status'=>'not-found',],200);
           
        }
    }
    // plans view
    public function plans()  {
     $plans=Plan::all();
     return view('reciption.plans.index',compact('plans'));
    }
    public function registerPlan($id) {
        $plan =Plan::findOrFail($id);
        return view('reciption.plans.register',compact('plan'));
    }
    public function subscribeToPlan(PlanSubscriptionRequest $request,$id) {
        $plan=Plan::findOrFail($id);
        $subscriber =$this->planService->register($request->validated(),'reception',$plan);
        if ($subscriber->payment_method == 'cash') {
            return redirect()->back()->with('success','customer subscribed successfully!');
        }elseif($subscriber->payment_method=='online'){
            return $this->paymentService->subscribe($subscriber);
        }
    }
    public function subscribers($id) {
        $plan=Plan::findOrFail($id);
        return view('reciption.plans.subscribers',compact('plan'));
    }
    public function subscriber($id) {
        $subscriber =Subscriber::findOrFail($id)->load('patient');
        return view('reciption.plans.subscriber',compact('subscriber'));
    }
    public function updateSubscriber(PlanSubscriptionRequest $request,$id) {
        $subscriber =Subscriber::findOrFail($id);
        $this->planService->update($request->validated(),$subscriber);
        return redirect()->back()->with('success','customer Updated successfully!');

    }
    public function destroySubscriber($id) {
        $subscriber =Subscriber::findOrFail($id);
        $plan_id =$subscriber->plan_id;
        $subscriber->delete();
        return to_route('reception.subscribers',$plan_id)->with('success','subscriber deleted successfully');
        
    }
  
 
   
}
