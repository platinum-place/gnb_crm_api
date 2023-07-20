<?php

namespace App\Providers;

use App\Helpers\ZohoHelper;
use App\Services\NavixyHelper;
use App\Services\SystrackHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
