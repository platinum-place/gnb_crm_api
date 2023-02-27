<?php

namespace App\Models\Api;

use App\Models\shared\ApiModel;
use App\Models\shared\IApiGps;
use App\Services\SystrackService;

class Systrack extends ApiModel implements IApiGps
{
    protected array $fillable = [
        "trackPoint", "calculatedSpeed", "deviceActivity", "username",
        "name", "surname", "email", "devices", "userTemplateID", "id",
    ];

    public function list()
    {
        $response = (new SystrackService)->list();
        return collect($response)->mapInto(self::class);
    }

    public function find(int $id)
    {
        $response = (new SystrackService)->list($id);
        $this->fill($response);
        return $this;
    }

    public function getTrackPoint(): array
    {
        return $this->trackPoint["position"];
    }
}
