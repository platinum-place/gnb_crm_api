<?php

namespace App\Models\shared;

abstract class ZohoModel extends ExtendedModel
{
    protected string $moduleName;

    protected string $token;

    function __construct()
    {
        $this->token = \Zoho::generateAccessToken();
    }

    public function allRecords(array $params = []): array
    {
        return \Zoho::getRecords($this->token, $this->moduleName, ...$params);
    }
}
