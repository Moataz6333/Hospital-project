<?php

use App\Http\Controllers\Api\HospitalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Hospital
Route::get('/hospital',[HospitalController::class,'hospital']);
// clinics
Route::get('/clinics',[HospitalController::class,'clinics']);
// clinic with id
Route::get('/clinic/{id}',[HospitalController::class,'clinic']);
// doctor with id
Route::get('/doctor/{id}',[HospitalController::class,'doctor'] );
// create appointment
Route::post('/appointment/create', [HospitalController::class,'createAppointment']);


