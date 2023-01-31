<?php

namespace App\Models\Api\shared;

use App\Services\ZohoService;
use Illuminate\Support\Collection;

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

    public function create(array $attributes = [])
    {
        $attributes = $this->setMutateable($attributes);

        $attributes = (empty($attributes)) ? $this->attributes : $attributes;

        $response = (new ZohoService)->create($this->moduleName, $attributes);

        if (isset($response["data"][0]["details"]["id"]))
            return $this->find($response["data"][0]["details"]["id"]);

        return null;
    }

    public function list(array $params = [])
    {
        $response = (new ZohoService)->getRecords($this->moduleName, ...$params);

        if (empty($response["data"]))
            return null;
            
        return ["data" => collect($response["data"]), "info" => $response["info"]];
    }
}
