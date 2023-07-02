<?php

namespace App\Providers;

use App\Helpers\PaginatorHelper;
use App\Helpers\ZohoHelper;
use App\Services\NavixyHelper;
use App\Services\SystrackHelper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('paginator', function () {
            return new PaginatorHelper();
        });

        $this->app->bind('Zoho', function () {
            return new ZohoHelper();
        });

        $this->app->bind('Systrack', function () {
            return new SystrackHelper();
        });

        $this->app->bind('Navixy', function () {
            return new NavixyHelper();
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
