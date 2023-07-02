<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Systrack extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Systrack';
    }
}
