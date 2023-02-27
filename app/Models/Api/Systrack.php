<?php

namespace App\Models\Api;

use App\Models\shared\ApiModel;
use App\Services\SystrackService;

class Systrack extends ApiModel
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
}
