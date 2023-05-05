<?php

namespace App\Repositories;

use App\Facades\Zoho;
use App\Repositories\shared\IApiRepository;

class ZohoRepository /* implements IApiRepository */
{
    protected string $module;

    public function __construct(string $module)
    {
        $this->module = $module;
    }

    public function list(array $params)
    {
        $query = Zoho::initialice($this->module);
        foreach ($params as $key => $value) {
            $query = $query->search($key, $value);
        }
        return $query->getRecords();
    }
}
