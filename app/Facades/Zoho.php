<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Zoho extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Zoho';
    }
}
