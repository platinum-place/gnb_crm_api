<?php

namespace App\Models\shared;

use App\Builders\NavixyBuilder;

class Navixy extends ApiModel
{
    protected array $fillable = [
        "id", "label", "group_id", "source", "tag_bindings", "satellites",
        "get_time", "heading", "speed", "lat", "lng",
    ];

    public function newBuilder()
    {
        return (new NavixyBuilder())->setModel($this);
    }

    public function getLocation()
    {
        return [
            "lat" => $this->lat,
            "lng" => $this->lng,
        ];
    }
}
