<?php

namespace App\Models\Api\shared;

use App\Services\SystrackService;

abstract class SystrackModel extends ApiModel
{
    public function list()
    {
        $response = (new SystrackService)->list();
        return collect($response)->mapInto(get_called_class());
    }

    public function find(int $id)
    {
        $response = (new SystrackService)->list($id);
        $this->fill($response);
        return $this;
    }
}
