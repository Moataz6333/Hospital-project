<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\ClinicResourse;
use App\Http\Resources\ClinicWithDoctorsResourse;
use App\Http\Resources\DoctorResource;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Interfaces\PatientInterface;

class HospitalController extends Controller
{
    protected $patientService;
    public function __construct(PatientInterface $patientService)
    {
        $this->patientService = $patientService;
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

            $appointment = new AppointmentResource( $this->patientService->register_Cash($validator->validated(), $doctor, $registration_method)) ;
            return response()->json([
                'message' => "Regestration Done Successfully",
                'appointment' => $appointment
            ], 200);
        } else {

            $appointment =  $this->patientService->register_Online($validator->validated(), $doctor, $registration_method);
            return response()->json([
                "message"=>"appointment created",
                "link"=>env("APP_URL")."/myfatoorah/checkout?oid={$appointment->id}"
            ], 200);
            // return redirect("myfatoorah/checkout?oid={$appointment->id}");
        }
    }
}
