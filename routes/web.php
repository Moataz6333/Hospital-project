<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmpController;
use App\Http\Controllers\ReciptionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);


Route::get('/', function () {
    return view('welcome', []);
});

Route::view('/login', 'login');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// auth
Route::middleware(['auth:sanctum', 'admin_only'])->group(function () {
    Route::view('/register', 'register');
    Route::post('/register', [UserController::class, 'store'])->name('users.store');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('hospital', [HospitalController::class, 'index'])->name('hospital');
    Route::post('hospital/update', [HospitalController::class, 'update'])->name('hospital.update');
    Route::resource('doctors', AdminController::class);
    Route::get('doctors/timeTable/{id}', [AdminController::class, 'timeTable'])->name('doctors.timeTable');
    Route::post('doctors/timeTable/{id}', [AdminController::class, 'timeUpdate'])->name('doctors.timeUpdate');
    Route::resource('clinics', ClinicController::class);
    Route::resource('employees', EmpController::class);
    Route::post('/employees/search', [EmpController::class, 'search'])->name('employees.search');
    Route::get('/AllEmployees', [EmpController::class, 'getAllEmployees'])->name('employees.json');
    Route::post('/doctors/search', [AdminController::class, 'search'])->name('doctors.search');
    Route::get('/AllDoctors', [AdminController::class, 'getAllDoctors'])->name('doctors.json');
    Route::get('/doctors/archive/{id}/{day?}', [AdminController::class, 'archive'])->name('doctors.archive');
    Route::get('/doctors/appointment/{id}', [AdminController::class, 'appointment'])->name('doctors.appointment.show');
});

// reciptionist
Route::middleware(['auth:sanctum', 'receptionist_only'])->group(function () {
    Route::get('/reception', [ReciptionController::class, 'index'])->name('reception.index');
    Route::post('/clinics/search', [ReciptionController::class, 'search'])->name('clinics.search');
    Route::get('/Allclinics', [ReciptionController::class, 'getAllClinics'])->name('clinics.json');
    Route::get('/clinic/show/{id}', [ReciptionController::class, 'showClinic'])->name('clinic.show');
    Route::get('/timeTable/{id}', [ReciptionController::class, 'timeTable'])->name('timeTable.show');
    Route::get('/reception/register/{id}', [ReciptionController::class, 'registerForm'])->name('registerForm');
    Route::post('/appointment/create/{id}', [ReciptionController::class, 'createAppointment'])->name('appointment.create');
    Route::get('/reception/appointments/{id}/{day?}/{week?}', [ReciptionController::class, 'appointments'])->name('reception.appointments');
    Route::get('/reception/appointment/{id}/{from?}', [ReciptionController::class, 'appointment'])->name('reception.appointment.show');
    Route::post('/appointment/update/{id}', [ReciptionController::class, 'updateAppointment'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [ReciptionController::class, 'deleteAppointment'])->name('appointment.destroy');
    Route::get('/reception/doctors/archive/{id}/{day?}', [ReciptionController::class, 'archive'])->name('reception.archive');
    Route::get('/reception/transactions/{id}', [ReciptionController::class, 'transactions'])->name('reception.transactions');
    Route::delete('/reception/transactions/delete/{id}', [ReciptionController::class, 'transaction_delete'])->name('transaction.delete');
    
    // Route::get('/test',[ReciptionController::class,'test']);

});

// doctor
Route::middleware(['auth:sanctum', 'doctor_only'])->group(function () {
    Route::get('/doctor/archive/{day?}', [DoctorController::class, 'archive'])->name('doctor.archive');
    Route::get('/doctor/{day?}', [DoctorController::class, 'index'])->name('doctor.index');
    Route::get('/doctor/appointment/{id}/{from?}', [DoctorController::class, 'appointment'])->name('doctor.appointment.show');
    Route::get('/appointment/done/{id}', [DoctorController::class, 'done'])->name('appointment.done');
    Route::get('/appointment/doctor/cancel/{id}', [DoctorController::class, 'cancel'])->name('appointment.doctor.cancel');
});

// superAdmin routes
Route::middleware(['auth:sanctum', 'superAdmin_only'])->group(function () {
    Route::resource('roles', RoleController::class); 
});

// hosptial
Route::get('/sheet/{id}', [HospitalController::class, 'sheet'])->name('hospital.sheet');
Route::get('/export/{id}', [HospitalController::class, 'exportSheet'])->name('sheet.download');
