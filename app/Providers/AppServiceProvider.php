<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        foreach (glob(app_path().'/Helpers/*.php') as $filename){
            require_once($filename);
        }

        foreach (glob(app_path().'/BFF/*.php') as $filename){
            require_once($filename);
        }

        require_once (base_path().'/modules/plugnpay-php/PnP.php');
        require_once (base_path().'/modules/plugnpay/PlugNPay.php');
    }
}
