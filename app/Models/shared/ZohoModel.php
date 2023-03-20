<?php

namespace App\Models\shared;

use App\Builders\ZohoBuilder;
use App\Facades\Zoho;

abstract class ZohoModel extends ApiModel
{
    protected string $module = '';

    public function belongToUser($id)
    {
        return $this->Account_Name["id"] == $id;
    }

    public function newBuilder()
    {
        return (new ZohoBuilder())->setModel($this);
    }

    public function getModule()
    {
        return $this->module;
    }
}
