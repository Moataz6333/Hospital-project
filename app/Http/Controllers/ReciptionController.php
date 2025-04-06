<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Interfaces\PatientInterface;
use App\Models\Hospital;
use App\Models\Transaction;

date_default_timezone_set('Africa/Cairo');


class ReciptionController extends Controller
{
    protected $patientService;
    public function __construct(PatientInterface $patientService)
    {
        $this->patientService = $patientService;
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
            return redirect("myfatoorah/checkout?oid={$appointment->id}");
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
        
        $appointment = Appointment::findOrFail($id);
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
                'paid' => 'nullable',
                
            ]
        );
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
   
}
