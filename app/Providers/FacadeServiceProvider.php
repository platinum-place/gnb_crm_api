<?php

namespace App\Providers;

use App\Helpers\BuilderHelper;
use App\Helpers\NavixyHelper;
use App\Helpers\SystrackHelper;
use App\Helpers\ZohoHelper;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('zoho', function () {
            return new ZohoHelper();
        });

        $this->app->bind('systrack', function () {
            return new SystrackHelper();
        });

        $this->app->bind('navixy', function () {
            return new NavixyHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
