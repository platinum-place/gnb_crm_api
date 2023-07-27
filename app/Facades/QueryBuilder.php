<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class QueryBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'querybuilder';
    }
}
