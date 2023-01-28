<?php

namespace App\Models\shared;

use App\Services\ZohoService;

abstract class ZohoModel extends ApiModel
{
    protected string $moduleName = '';

    public function find(int|string $id)
    {
        $response = (new ZohoService)->getRecord($this->moduleName, $id);
        $this->fill($response["data"][0]);
        return $this;
    }
}
