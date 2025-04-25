<?php

namespace App\Http\Controllers;

use App\Interfaces\BalanceInterface;
use App\Jobs\BalanceUpdatedJob;
use App\Models\Appointment;
use App\Models\Hospital;
use App\Models\Transaction;
use Illuminate\Http\Request;
date_default_timezone_set('Africa/Cairo');
class DashboardController extends Controller
{
    
    protected $hospital;
    protected $balanceService;
    public function __construct(BalanceInterface $balanceService) {
       $this->balanceService=$balanceService;
       $this->hospital = Hospital::first();
    }
   public function dashboard() {
    
        $balance =$this->balanceService->getBalance();
        $currentWeek=date_create();
        $weekData=$this->balanceService->weekData(clone $currentWeek);
        $weeks=$this->balanceService->weeks();
        $months=$this->balanceService->months();
        
        $transactions=Transaction::orderBy('created_at','desc')->take(5)->get();
           return view('admin.dashboard',compact('balance','weekData','currentWeek','weeks','transactions','months'));
    }
    public function increase($amount)  {
        $this->balanceService->increase($amount);
        echo (double) $amount;
        BalanceUpdatedJob::dispatch();
    }
    public function total()  {
        return response()->json([
           'total'=> $this->balanceService->getBalance()]);
    }
    public function getWeek($week) {
        $date=date_create($week);
        return response()->json($this->balanceService->weekData($date), 200);
    }
    public function transactions()  {
        $transactions =Transaction::all()->reverse();
        return view('admin.transactions',compact('transactions'));
    }

 
   
}
