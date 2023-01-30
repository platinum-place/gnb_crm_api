<?php

namespace App\Models\Api\shared;

use App\Services\ZohoService;

abstract class ZohoModel extends ApiModel
{
    protected string $moduleName = '';

    public function find(int|string $id)
    {
        $response = (new ZohoService)->getRecord($this->moduleName, $id);

        if (isset($response["status"]) and $response["status"] == "error")
            return null;


        $this->fill($response["data"][0]);
        return $this;
    }
}
