<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PatientServeice;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientServeice $patientService) {
        $this->patientService = $patientService;
    }
    
    
}
