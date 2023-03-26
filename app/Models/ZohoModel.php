<?php

namespace App\Models;

use App\Builders\ZohoBuilder;
use App\Models\shared\ApiModel;

class ZohoModel extends ApiModel
{
    public string $module = "";

    public function newQuery()
    {
        return new ZohoBuilder($this);
    }

    public function where($field, $operator = "", $value = "", $concatenator = "and")
    {
        return $this->newQuery()->where($field, $operator, $value, $concatenator);
    }

    public function find(int $id)
    {
        return $this->newQuery()->find($id);
    }
}
