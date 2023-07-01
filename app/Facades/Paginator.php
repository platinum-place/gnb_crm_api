<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Paginator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'paginator';
    }
}
