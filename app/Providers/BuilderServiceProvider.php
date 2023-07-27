<?php

namespace App\Providers;

use App\Facades\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class BuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Builder::macro('filterPaginate', function (array $params) {
            /** @var Builder */
            $builder = $this;

            return QueryBuilder::filterBuilder($builder, $params);
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
