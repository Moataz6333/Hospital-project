<?php

namespace App\Services;

use App\Interfaces\BalanceInterface;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Donation;
use App\Models\Employee;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class EGP_balanceService implements BalanceInterface
{
    protected $hospital;
    public function __construct()
    {
        $this->hospital = Hospital::first();
    }

    public function getBalance()
    {
        $this->updateBalance();
        return [
            "cash" => $this->getCash(),
            "transactions" => $this->getTransactions(),
            "donations" => $this->getdonations()['total'],
            "subscribers"=> $this->getSubscribtions(),
            "total" => round($this->hospital->balance)
        ];
    }
    // update balance
    public function updateBalance() {
        $this->hospital->balance =array_sum([$this->getCash(),$this->getdonations()['total'],$this->getTransactions(),$this->getSubscribtions()]);
        $this->hospital->save();
    }
    // online trunsactions
    public function getTransactions()
    {
        $transactions = Transaction::where('service', 'myfatoorah')->where('donation_id', null)->where('subsciber_id', null)->sum('InvoiceValue');
        $transactions *= (float) config('app.rate');
        return round($transactions);
    }
    // online donations
    public function getdonations()
    {
        $transactions = Transaction::where('service', 'myfatoorah')->whereNotNull('donation_id')->sum('InvoiceValue');
        $cash = Donation::where('payment_method', 'cash')->where('paid', true)->sum('value');
        return [
            'transactions' => $transactions,
            'cash' => $cash,
            'total' => $transactions + $cash
        ];
    }
    // subscribtions
    public function getSubscribtions() {
       $plans =Plan::all()->load('subscribers');
        $total =0;
        foreach ($plans as $plan ) {
            $total += count($plan->subscribers) * $plan->price;
        }
        return $total;
    }
    public function getCash()
    {
        $appointments = Appointment::where('payment_method', 'cash')->where('paid', true)->get();
        $cash = 0;
        foreach ($appointments as $appointment) {
            $cash += $appointment->doctor->price;
        }
        return $cash;
    }
    public function getSalaries()
    {
        $doctors_salaries = Doctor::sum('salary');
        $employees_salaries = Employee::sum('salary');
        return $doctors_salaries + $employees_salaries;
    }
    public function increase($amount)
    {

        $this->hospital->increaseBalance((float)$amount);
    }
    // get all weeks
    public function weeks()
    {
        $first_appoinemnt = date_create(Appointment::min('date'));
        $now = date_create();
        $weeks = [];
        while ($first_appoinemnt < $now) {
            $week = clone $first_appoinemnt;
            $weeks[] = $week->format("d-M-Y");
            $first_appoinemnt->modify('next sunday');
        }
        return array_reverse($weeks);
    }
    public function weekData($week)
    {
        $date = $week;

        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        $apps = [];
        $total = 0;
        $count = 0;
        foreach ($days as $day) {
            $app_date = clone $date->modify("this $day");
            $sum = Appointment::where('date', $app_date->format("Y-m-d"))->where('paid', true)->sum('amount_paid');
            $count += Appointment::where('date', $app_date->format("Y-m-d"))->where('paid', true)->count();
            $apps[$day] = $sum;
            $total += $sum;
        }

        return [
            "days" => $apps,
            "count" => $count,
            "max" => max($apps),
            "ave" => array_sum($apps) / 5,
            "min" => min($apps),
            "total" => $total
        ];
    }
    public function months() {
        $currentMonth=date_create("Dec 2025");
        $min=date_create(Appointment::min('created_at'));
        $months=[]; //2025-2
        while ($currentMonth >= $min) {
            $month=clone $min;
            $months[]=$month->format("Y-m");
            $min->modify("next month");
        }
            $data=[];
        foreach ($months as $month ) {
         $data[date_create($month)->format("M-Y")]= Appointment::where('date', 'like', '%' . $month . '%')->sum('amount_paid');
        }
            
            return $data;
    }
}
