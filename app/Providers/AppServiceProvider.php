<?php

namespace App\Providers;

use App\Services\NavixyService;
use App\Services\SystrackService;
use App\Services\ZohoService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('Zoho', function () {
            return new ZohoService();
        });

        $this->app->bind('Systrack', function () {
            return new SystrackService();
        });

        $this->app->bind('Navixy', function () {
            return new NavixyService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
