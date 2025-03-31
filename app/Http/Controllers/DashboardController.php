<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $hospital;
    public function __construct() {
        dd($this->getSalaries());
        $this->hospital = Hospital::first();
    }
   public function dashboard() {
        $total =$this->getBalance();

           return view('admin.dashboard',compact('total'));
    }
    private function getBalance()  {
      return $this->getTransactions() + $this->getCash();
    }
    private function getTransactions() {
        $transactions=Transaction::where('service','myfatoorah')->sum('InvoiceValue');
        $transactions *= (double) config('app.rate');
        return $transactions;
    }
    private function getCash()  {
        $appointments=Appointment::where('payment_method','cash')->get();
        $cash=0;
        foreach ($appointments as $appointment) {
            $cash += $appointment->doctor->price;
        }
        return $cash;
    }
    private function getSalaries() {
        $doctors_salaries=Doctor::sum('salary');
        $employees_salaries=Employee::sum('salary');
        return $doctors_salaries + $employees_salaries;
    }
   
}
