<?php

namespace App\Models\shared;

use App\Builders\SystrackBuilder;

class Systrack extends ApiModel
{
    protected array $fillable = [
        "trackPoint", "calculatedSpeed", "deviceActivity", "username",
        "name", "surname", "email", "devices", "userTemplateID", "id",
    ];

    public function newBuilder()
    {
        return (new SystrackBuilder())->setModel($this);
    }

    public function getLocation()
    {
        return [
            "lat" => $this->trackPoint["position"]["latitude"],
            "lng" => $this->trackPoint["position"]["longitude"],
        ];
    }
}
