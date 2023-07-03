<?php

namespace App\Repositories\shared;

use App\Facades\Zoho;
use Illuminate\Pagination\LengthAwarePaginator;

class ZohoRepository implements IApiRepository
{
    protected string $module;

    public function __construct(string $module)
    {
        $this->module = $module;
    }

    public function list(array $params = []): LengthAwarePaginator
    {
        return Zoho::getRecords($this->module, $params);
    }

    public function create(array $params): array
    {
        $id = Zoho::createRecord($this->module, $params);

        return Zoho::getRecord($this->module, $id);
    }

    public function find(string $id): array
    {
        return Zoho::getRecord($this->module, $id);
    }
}
