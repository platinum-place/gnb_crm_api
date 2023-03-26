<?php

namespace App\Models;

class ZohoCase extends ZohoModel
{
    public string $module = "Cases";

    public function gpsProvider()
    {
        if ($this->Product_Name)
            return (new ZohoProduct)->find($this->Product_Name["id"])->location;
    }

    public function isFinished(): bool
    {
        if ($this->Status)
            return in_array(
                $this->Status,
                [
                    "Medio servicio",
                    "Cancelado",
                    "Contacto",
                    "Cerrado",
                ]
            );
    }
}
