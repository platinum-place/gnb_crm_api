<?php

namespace App\Repositories;

use App\Models\shared\ZohoModel;
use App\Repositories\shared\IApiRepository;

class Repository implements IApiRepository
{
    protected ZohoModel $model;

    public function __construct(ZohoModel $model)
    {
        $this->model = $model;
    }

    public function list(array $params)
    {
        $query = $this->model;
        foreach ($params as $key => $value) {
            $query = $query->where($key, $value);
        }
        return $query->get();
    }

    public function getById(string|int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }
}
