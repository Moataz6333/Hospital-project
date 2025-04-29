<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanSubscriptionRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\ClinicResourse;
use App\Http\Resources\ClinicWithDoctorsResourse;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\EventResorce;
use App\Http\Resources\PlanResource;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Interfaces\PatientInterface;
use App\Interfaces\PaymentInterface;
use App\Models\Eventt;
use App\Models\Plan;
use App\Services\DonationService;
use App\Services\PlanService;

class HospitalController extends Controller
{
    protected $patientService;
    protected $paymentService;
    protected $planService;
    public function __construct(
        PatientInterface $patientService,
        PaymentInterface $paymentService,
        PlanService $planService
    ) {
        $this->patientService = $patientService;
        $this->paymentService = $paymentService;
        $this->planService = $planService;
    }
    public function Hospital()
    {
        $hospital = Hospital::first();
        return response()->json($hospital, 200);
    }
    public function clinics()
    {
        return response()->json(ClinicResourse::collection(Clinic::all()), 200);
    }
    public function clinic($id)
    {
        $clinic = Clinic::findOrfail($id);
        return response()->json(new ClinicWithDoctorsResourse($clinic), 200);
    }
    public function doctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        return response()->json(new DoctorResource($doctor), 200);
    }
    // create Appointment 
    public function createAppointment(Request $request)
    {

        $doctor = Doctor::findOrFail($request->doctor_id);
        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'phone' => ['required', 'max:13'],
                'national_id' => ['required'],
                'age' => ['required'],
                'gender' => ['required', Rule::in(['male', 'female'])],
                'day' => ['required'],
                'date' => ['required'],
                'type' => ['required', Rule::in(['examination', 'consultation'])],
                'payment_method' => ['required', Rule::in(['cash', 'online'])],


            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),  // Return validation errors
            ], 422);
        }
        if (substr($request->day, 0, 3) !== date_format(date_create($request->date), 'D')) {
            return response()->json([
                'message' => 'date must match day',
            ], 422);
        }


        $registration_method = 'website';
        if ($request->payment_method == 'cash') {

            $appointment = new AppointmentResource($this->patientService->register_Cash($validator->validated(), $doctor, $registration_method));
            return response()->json([
                'message' => "Regestration Done Successfully",
                'appointment' => $appointment
            ], 200);
        } else {

            $appointment =  $this->patientService->register_Online($validator->validated(), $doctor, $registration_method);
            return $this->paymentService->pay($appointment);
            // return redirect("myfatoorah/checkout?oid={$appointment->id}");
        }
    }
    // donate
    public function donate(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'phone' => ['required', 'max:13'],
                'national_id' => ['required', 'max:50'],
                'value' => ['required'],
                'currency' => ['required', Rule::in(['EGP', 'KWD'])],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),  // Return validation errors
            ], 422);
        }
        $donationService = new DonationService();
        $donation = $donationService->registerOnline($validator->validated(), 'online');
        return $this->paymentService->donate($donation);
    }
    // has a discount
    public function hasDiscount(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'national_id' => ['required'],
                'doctor_id' => ['required'],

            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),  // Return validation errors
            ], 422);
        }
        $national_id = $request->national_id;
        $doctor_id = $request->doctor_id;
        $patient = Patient::where('national_id', $national_id)->first();
        $doctor = Doctor::findOrFail($doctor_id);
        if ($patient) {
            $appointments = count($patient->appointments->where('doctor_id', $doctor->id));
            $discounts = Discount::all();
            $patient_discount = 0;
            $discount_name = "";
            foreach ($discounts as $discount) {
                if (str_contains($discount->name, 'Appointments')) {
                    $number = (int) explode('-', $discount->name)[0];
                    if ($appointments >= $number) {
                        $patient_discount = $discount->discount;
                        $discount_name = $discount->name;
                    }
                }
            }
            if ($patient->donation) {
                $patient_discount = Discount::where('name', 'donator')->first()->discount;
                $discount_name = 'Donator';
            }
            if ($patient_discount) {
                $price = $doctor->price - ($patient_discount * $doctor->price);
                $data = [
                    'status' => 'found',
                    'message' => "this patient has a $discount_name discount",
                    'price' => $price
                ];
                return response()->json($data, 200);
            }
            return response()->json(['status' => 'not-found',], 200);
        } else {
            return response()->json(['status' => 'not-found',], 200);
        }
    }
    // plans
    public function plans()
    {
        return response()->json(PlanResource::collection(Plan::all()), 200);
    }
    // plan
    public function plan($id)
    {
        $plan = Plan::findOrfail($id);
        return response()->json(new PlanResource($plan), 200);
    }
    // subscriber
    public function subscribe(PlanSubscriptionRequest $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $subscriber = $this->planService->register_online($request->validated(), $plan);
        return $this->paymentService->subscribe($subscriber);
    }
    // events
    public function events() {
        return response()->json(EventResorce::collection(Eventt::all()), 200);
    }
    // event
    public function event($id) {
        $event = Eventt::findOrfail($id);
        return response()->json(new EventResorce($event), 200);
    }
    //follow event
    public function event_register(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $follower = $this->planService->follow($request->email, $id);
        if ($follower) {
            return response()->json([
                "message"=>"follower registered successfully",
                "follower"=>$follower
            ], 200);
        } else {
            return response()->json([
                "message"=>"Email Already Exists with this event!"
            ], 200);
        }
    }

}
