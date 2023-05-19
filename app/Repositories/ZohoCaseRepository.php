<?php

namespace App\Repositories;

use App\Models\ZohoCase;
use App\Repositories\shared\ZohoRepository;

class ZohoCaseRepository extends ZohoRepository
{
    protected ZohoCase $model;

    public function __construct(ZohoCase $model)
    {
        $this->model = $model;
    }

    public function list(array $params)
    {
        $query = $this->model->newQuery();

        foreach ($params as $key => $value)
            $query->where($key, $value);

        return $query->get(
            per_page: $params['per_page'] ?? 10,
            page: $params['page'] ?? null,
            sort_by: $params['sort_by'] ?? null,
            sort_order: $params['sort_order'] ?? null
        );
    }
}
