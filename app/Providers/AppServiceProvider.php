<?php

namespace App\Providers;

use App\Helpers\EloquentBuilderHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::macro('filterPaginate', function (array $params) {
            /** @var Builder */
            $builder = $this;

            return (new EloquentBuilderHelper)->filterBuilder($builder, $params);
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
