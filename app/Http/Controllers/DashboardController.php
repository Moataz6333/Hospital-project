<?php

namespace App\Http\Controllers;

use App\Interfaces\BalanceInterface;
use App\Jobs\BalanceUpdatedJob;
use App\Models\Hospital;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $hospital;
    protected $balanceService;
    public function __construct(BalanceInterface $balanceService) {
       $this->balanceService=$balanceService;
       $this->hospital = Hospital::first();
    }
   public function dashboard() {
        $total =$this->balanceService->getBalance();
        // dd($total);
           return view('admin.dashboard',compact('total'));
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
 
   
}
