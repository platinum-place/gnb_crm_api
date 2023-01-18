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

    public function findRecord(int $id): array
    {
        return \Zoho::getRecord($this->token, $this->moduleName, $id);
    }

    public function createRecord(array $body): array
    {
        return \Zoho::create($this->token, $this->moduleName, $body);
    }
}
