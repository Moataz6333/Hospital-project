<?php

namespace App\Http\Controllers;

use App\Interfaces\BalanceInterface;
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
 
   
}
