<?php

namespace App\Repositories\Zoho;

use App\Facades\Zoho;
use App\Models\shared\ZohoModel;
use App\Repositories\shared\ApiRepository;
use App\Repositories\shared\IApiRepository;

abstract class ZohoRepository implements IApiRepository
{
    protected ZohoModel $model;

    public function __construct(ZohoModel $model)
    {
        $this->model = $model;
    }

    public function list(array $params)
    {
        foreach ($params as $key => $value) {
            $this->model->where($key, $value);
        }
        return $this->model->get(true);
    }
}
