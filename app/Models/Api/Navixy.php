<?php

namespace App\Models\Api;

use App\Models\shared\ApiModel;
use App\Services\NavixyService;

class Navixy extends ApiModel
{
    protected array $fillable = [
        "id", "label", "group_id", "source", "tag_bindings", "satellites",
        "get_time", "heading", "speed", "lat", "lng",
    ];

    public function list()
    {
        $response = (new NavixyService)->list();
        return collect($response["list"])->mapInto(self::class);
    }

    public function find(int $id)
    {
        $response = (new NavixyService)->find($id);
        $this->fill($response["value"]);
        return $this;
    }

    public function getTrackPoint()
    {
        return [
            "lat" => $this->lat,
            "lng" => $this->lng,
        ];
    }
}
