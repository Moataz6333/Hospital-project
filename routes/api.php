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
//donation
Route::post('/donate', [HospitalController::class,'donate']);
// has discount
Route::post('/hasDiscount', [HospitalController::class,'hasDiscount']);
// all plans
Route::get('/plans',[HospitalController::class,'plans'] );
// plan with id
Route::get('/plan/{id}',[HospitalController::class,'plan'] );
// subscribe to plan
Route::post('/subscribe/{id}', [HospitalController::class,'subscribe']);
// events
Route::get('events',[HospitalController::class,'events']);
// event with id
Route::get('event/{id}',[HospitalController::class,'event']);
// subscribe for event
Route::post('/event/register/{id}', [HospitalController::class,'event_register']);

