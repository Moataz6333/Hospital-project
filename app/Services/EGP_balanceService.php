<?php

namespace App\Services;

use App\Interfaces\BalanceInterface;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Transaction;

class EGP_balanceService implements BalanceInterface{
    protected $hospital;
    public function __construct() {
        $this->hospital = Hospital::first();
    }

    public function getBalance(){
        return round($this->hospital->balance);
      }
      public function getTransactions() {
          $transactions=Transaction::where('service','myfatoorah')->sum('InvoiceValue');
          $transactions *= (double) config('app.rate');
          return $transactions;
      }
      public function getCash()  {
          $appointments=Appointment::where('payment_method','cash')->get();
          $cash=0;
          foreach ($appointments as $appointment) {
              $cash += $appointment->doctor->price;
          }
          return $cash;
      }
      public function getSalaries() {
          $doctors_salaries=Doctor::sum('salary');
          $employees_salaries=Employee::sum('salary');
          return $doctors_salaries + $employees_salaries;
      }
      public function increase($amount)  {
       
        $this->hospital->increaseBalance((double)$amount);
      
       
      }

}