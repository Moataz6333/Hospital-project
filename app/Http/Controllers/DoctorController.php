<?php

namespace App\Http\Controllers;

use App\Interfaces\PatientInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Services\PaymentService;
use Carbon\Carbon;
use MyFatoorah\Library\MyFatoorah;



class DoctorController extends Controller
{
    protected $patientService;
    public function __construct(PatientInterface $patientService)
    {
        $this->patientService = $patientService;
    }
    public function index($current = null)
    {
        $doctor = auth()->user()->doctor;
        $week = "this";
        $days = GetDaysNames(TimesWhereNotNull($doctor));
        if ($current == null) {
            $current = GetCurrentDay($days);
        }
        $today = new \DateTime("$week $current");
        $date = $today->format("Y-m-d");
        if ($week == "next") {
            $today->modify("+1 week");
        }
        $appointments = $doctor->appointments->where('date', date($date))->where('status', 'pending')->load('patient');


        return view('doctors.index', compact('doctor', 'days', 'current', 'appointments', 'week', 'date'));
    }
    public function appointment($id, $from = null)
    {

        $appointment = Appointment::findOrFail($id)->load('patient');
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
        return view('doctors.appointment', compact('appointment', 'times', 'days', 'from'));
    }
    public function archive($current = null)
    {
        $doctor = auth()->user()->doctor;
        $days = GetDaysNames(TimesWhereNotNull($doctor));
        if ($current == null) {
            $current = GetCurrentDay($days);
        }
        $appointments = $this->patientService->archive($doctor, $current);
        return view('doctors.archive', compact('doctor', 'appointments', 'days', 'current'));
    }
    public function done($id)
    {
        $appointment = Appointment::findOrFail($id);
        if ($appointment->paid) {
            $appointment->status = "done";
            $appointment->save();
            return redirect()->back()->with('success', 'Appointment Done Successfully!');
        } else {
            return redirect()->back()->withErrors('error', 'Appointment did not paid!')->withInput();
        }
    }
    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->patientService->cancel($appointment, 'doctor');
        return redirect()->back()->with('success', 'Appointment canceled successfully!');
    }
   

    
}
