<?php

namespace App\Services;

use App\Interfaces\PatienInterface;
use App\Models\Patien;
use App\Models\Appointment;
use App\Jobs\NewAppointmentJob;

class PatientServeice implements PatienInterface
{
    

    public function register_Cash($data, $doctor, $registration_method)
    {

        $patien = new Patien();
        $patien->name = $data['name'];
        $patien->phone = $data['phone'];
        $patien->save();
        $appointment = new Appointment();
        $appointment->patien_id = $patien->id;
        $appointment->doctor_id = $doctor->id;
        $appointment->date = $data['date'];
        $appointment->type = $data['type'];
        $appointment->payment_method = $data['payment_method'];
        $appointment->registration_method = $registration_method;
        if ($registration_method=="reception" && key_exists("paid", $data)) {
            $appointment->paid = true;
        }
        $appointment->save();
        NewAppointmentJob::dispatch($appointment);
        return $appointment;
    }
    public function register_Online($data, $doctor, $registration_method)
    {

        $patien = new Patien();
        $patien->name = $data['name'];
        $patien->phone = $data['phone'];
        $patien->save();
        $appointment = new Appointment();
        $appointment->patien_id = $patien->id;
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
        $patien = $appointment->patien;
        $patien->name = $data['name'];
        $patien->phone = $data['phone'];
        $patien->save();
        $appointment->date = $data['date'];
        $appointment->type = $data['type'];
        $appointment->payment_method = $data['payment_method'];
        if (key_exists("paid", $data)) {
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

        $appointments = $doctor->appointments->whereIn('date', $weeks);
        // currentWeek
        $currentWeekAppointments = $doctor->appointments->where('date', $currentWeek->modify("this week $current")->format("Y-m-d"))->whereIn('status', ['done', 'canceled']);
        $appointments = $appointments->concat($currentWeekAppointments)->sortByDesc('created_at');

        return $appointments;
    }
    public function cancel($appointment, $canceldBy)
    {
        if (!in_array($canceldBy, ['doctor', 'patien'])) {
            return redirect()->back()->with('error', 'something went wrong');
        }
        $appointment->status = "canceled";
        $appointment->canceled_by = $canceldBy;
        $appointment->save();
    }
}
