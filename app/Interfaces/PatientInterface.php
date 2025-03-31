<?php

namespace App\Interfaces;

interface PatientInterface{
    public function register_Cash($data,$doctor,$registration_method);
    public function register_Online($data,$doctor,$registration_method);
    public function updateAppointment($appointment,$data);
    public function archive($doctor,$current);
    public function cancel($appointment,$canceldBy);
}