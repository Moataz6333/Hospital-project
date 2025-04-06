<?php

namespace App\Services;

use App\Interfaces\BalanceInterface;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\Patient;
use App\Models\Transaction;

class KDW_balance implements BalanceInterface{

    public function getBalance()  {
        return $this->getTransactions() + $this->getCash();
      }
      public function getTransactions() {
          $transactions=Transaction::where('service','myfatoorah')->sum('InvoiceValue');
        //   $transactions *= (double) config('app.rate');
          return $transactions;
      }
      public function getCash()  {
          $appointments=Appointment::where('payment_method','cash')->get();
          $cash=0;
          foreach ($appointments as $appointment) {
              $cash += $appointment->doctor->price;
          }
          $cash /= (double) config('app.rate');
          return $cash;
      }
      public function getSalaries() {
          $doctors_salaries=Doctor::sum('salary');
          $employees_salaries=Employee::sum('salary');
          return ($doctors_salaries + $employees_salaries)/(double) config('app.rate');
      }

}