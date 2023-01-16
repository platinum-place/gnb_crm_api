<?php

namespace App\Models\shared;

abstract class ZohoModel extends ExtendedModel
{
    protected string $moduleName;

    protected string $token;

    function __construct()
    {
        $this->token = \Zoho::generateToken();
    }

    public function getRecords(): array
    {
        return \Zoho::getAllRecords($this->token, $this->moduleName);
    }
}
