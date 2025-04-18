<?php

namespace App\Providers;

use App\Interfaces\BalanceInterface;
use App\Interfaces\PatientInterface;
use App\Interfaces\PaymentInterface;
use App\Services\EGP_balanceService;
use App\Services\KDW_balance;
use App\Services\myfatoorahPaymentService;
use App\Services\PatientServeice;
use Illuminate\Support\ServiceProvider;

// use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PatientInterface::class,function ()  {
                return new PatientServeice();
        });
        $this->app->bind(BalanceInterface::class,function ()  {
                if(config('app.currency') == "EGP"){
                    return new EGP_balanceService();
                }else if(config('app.currency') == "KDW"){
                    return new KDW_balance();
                }
        });
        // payment
        $this->app->bind(PaymentInterface::class,function () {
            if (config('app.payment_service')=='myfatoorah') {
                return new myfatoorahPaymentService();
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      

    }
}
