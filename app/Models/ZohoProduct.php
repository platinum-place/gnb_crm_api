<?php

namespace App\Models;

use App\Facades\Navixy;
use App\Facades\Systrack;

class ZohoProduct extends ZohoModel
{
    public string $module = "Products";

    public function location()
    {
        if ($this->Plataforma_API)
            return match ($this->Plataforma_API) {
                'Systrack' => Systrack::getLocation($this->Clave_API),
                'Navixy' => Navixy::getLocation($this->Clave_API),
            };
    }
}
