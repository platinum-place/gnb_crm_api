<?php

namespace App\Models\shared;

use App\Builders\ZohoBuilder;

abstract class ZohoModel extends ApiModel
{
    protected string $modelName = '';

    public function newQuery()
    {
        return new ZohoBuilder($this->modelName, $this);
    }
}
