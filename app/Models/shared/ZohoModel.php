<?php

namespace App\Models\shared;

use App\Services\ZohoService;

abstract class ZohoModel extends ApiModel
{
    protected string $moduleName = '';

    public function find(int|string $id)
    {
        $response = (new ZohoService)->getRecord($this->moduleName, $id);

        if (isset($response["status"]) and $response["status"] == "error" or empty($response))
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

    public function list(array $params = [], bool $all = false)
    {
        $response = (new ZohoService)->getRecords($this->moduleName, ...$params);

        if (isset($response["status"]) and $response["status"] == "error")
            return null;

        $collection = collect($response["data"])->mapInto(get_called_class());

        return match ($all) {
            true => [$collection, $response["info"]],
            false => $collection,
        };
    }
}