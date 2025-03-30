<?php

namespace App\Providers;

use App\Interfaces\PatienInterface;
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
        $this->app->bind(PatienInterface::class,function ()  {
                return new PatientServeice();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      

    }
}
