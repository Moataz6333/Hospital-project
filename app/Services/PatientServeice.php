<?php

namespace App\Services;

use App\Interfaces\PatientInterface;
use App\Jobs\BalanceUpdatedJob;
use App\Models\Patient;
use App\Models\Appointment;
use App\Jobs\NewAppointmentJob;
use App\Models\Discount;
use App\Models\Hospital;

class patientServeice implements PatientInterface
{
    
    public function register_Cash($data, $doctor, $registration_method)
    {

        $patient = Patient::firstOrCreate([
            'national_id'=>$data['national_id']
        ],[
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'gender'=>$data['gender'],
            'name'=>$data['name'],
            'age'=>(int)$data['age'],
        ]);
       
        $appointment = new Appointment();
        $appointment->patient_id = $patient->id;
        $appointment->doctor_id = $doctor->id;
        $appointment->date = $data['date'];
        $appointment->type = $data['type'];
        $appointment->payment_method = $data['payment_method'];
        $appointment->registration_method = $registration_method;
        if ($registration_method == "reception" && key_exists("paid", $data)) {
            $appointment->paid = true;
            Hospital::first()->increaseBalance($this->price($patient,$appointment->doctor,$appointment->doctor->price));
            BalanceUpdatedJob::dispatch();
        }
        $appointment->save();
        NewAppointmentJob::dispatch($appointment);
       
        return $appointment;
    }
    public function register_Online($data, $doctor, $registration_method)
    {

        $patient = new Patient();
        $patient->name = $data['name'];
        $patient->phone = $data['phone'];
        $patient->gender = $data['gender'];
        $patient->national_id = $data['national_id'];
        $patient->age = (int) $data['age'];
        $patient->save();
        $appointment = new Appointment();
        $appointment->patient_id = $patient->id;
        $appointment->doctor_id = $doctor->id;
        $appointment->date = $data['date'];
        $appointment->type = $data['type'];
        $appointment->payment_method = $data['payment_method'];
        $appointment->registration_method = $registration_method;

        $appointment->save();
        NewAppointmentJob::dispatch($appointment);
        return $appointment;
    }
    public function updateAppointment($appointment, $data)
    {
       
        $patient = $appointment->patient;
        $patient->name = $data['name'];
        $patient->phone = $data['phone'];
        $patient->gender = $data['gender'];
        $patient->national_id = $data['national_id'];
        $patient->age = (int) $data['age'];
        $patient->save();
        $appointment->date = $data['date'];
        $appointment->type = $data['type'];
        $appointment->payment_method = $data['payment_method'];
        if (key_exists("paid", $data)) {
            Hospital::first()->increaseBalance($appointment->doctor->price);
            $appointment->paid = true;
        } else {
            $appointment->paid = false;
        }
        $appointment->save();
    }
    public function archive($doctor, $current)
    {
        //all archived
        // all last sundays
        $firstWeek = $doctor->appointments->min('date');
        $firstWeek = date_create($firstWeek)->modify("this week $current");
        $currentWeek = date_create($current);
        $weeks = [];

        // dd( $firstWeek);
        while ($currentWeek > $firstWeek) {
            $week = clone $firstWeek;
            array_push($weeks, $week->format("Y-m-d"));
            $firstWeek->modify('+1 week');
        }
        // dd($weeks);

        $appointments = $doctor->appointments->whereIn('date', $weeks);
        // currentWeek
        $currentWeekAppointments = $doctor->appointments->where('date', $currentWeek->modify("this week $current")->format("Y-m-d"))->whereIn('status', ['done', 'canceled']);
        $appointments = $appointments->concat($currentWeekAppointments)->sortByDesc('created_at');

        return $appointments;
    }

    public function cancel($appointment, $canceldBy)
    {
        if (!in_array($canceldBy, ['doctor', 'patient'])) {
            return redirect()->back()->with('error', 'something went wrong');
        }
        $appointment->status = "canceled";
        $appointment->canceled_by = $canceldBy;
        if ($appointment->paid && $appointment->registration_method == 'reception') {
            Hospital::first()->decreaseBalance($appointment->doctor->price);
        }
        $appointment->save();
    }
    public function price($patient ,$doctor,$price) {
        
        $appointments = count($patient->appointments->where('doctor_id',$doctor->id));
        $discounts =Discount::all();
        $patient_discount=0;
        foreach ($discounts as $discount) {
            if (str_contains($discount->name,'Appointments')) {
                $number = (int) explode('-',$discount->name)[0];
                if($appointments >= $number){
                    $patient_discount=$discount->discount;
                }
            }
        }
        // if donation
        if ($patient->donation) {
            $patient_discount=Discount::where('name','donator')->first()->discount;
        }
        $price = $price - ($patient_discount*$price);
        return $price;
    }

}
