<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Navixy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'navixy';
    }
}
