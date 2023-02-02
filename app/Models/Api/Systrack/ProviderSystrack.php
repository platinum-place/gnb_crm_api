<?php

namespace App\Models\Api\Systrack;

use App\Models\Api\shared\SystrackModel;

class ProviderSystrack extends SystrackModel
{
    protected array $fillable = [
        "trackPoint",
        "calculatedSpeed",
        "deviceActivity",
        "username",
        "name",
        "surname",
        "email",
        "devices",
        "userTemplateID",
        "id",
    ];

    public function location()
    {
        if (is_array($this->trackPoint))
            return $this->trackPoint["position"];
    }
}
