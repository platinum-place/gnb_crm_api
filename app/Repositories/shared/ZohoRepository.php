<?php

namespace App\Repositories\shared;

use App\Builders\ZohoBuilder;
use App\Repositories\shared\IApiRepository;
use App\Models\shared\ZohoModel;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class ZohoRepository implements IApiRepository
{
    protected ZohoModel $model;

    public function __construct(ZohoModel $model)
    {
        $this->model = $model;
    }

    private function buildQuery(): ZohoBuilder
    {
        return $this->model->newQuery();
    }

    private function FilterList(ZohoBuilder $list, array $params): ZohoBuilder
    {
        foreach ($params as $key => $value)
            $list->where($key, $value);

        return $list;
    }

    private function paginate(ZohoBuilder $list, array $params): LengthAwarePaginator
    {
        return $list->get(
            per_page: $params['per_page'] ?? 10, //TODO: hacer constante de limite de paginacion
            page: $params['page'] ?? null,
            sort_by: $params['sort_by'] ?? null,
            sort_order: $params['sort_order'] ?? null
        );
    }

    public function list(array $params): LengthAwarePaginator
    {
        $list = $this->buildQuery();

        $list = $this->FilterList($list, $params);

        $list = $this->paginate($list, $params);

        return $list;
    }

    public function getById(int|string $id): ZohoModel
    {
        return $this->buildQuery()->find($id);
    }

    public function create(array $attributes): ZohoModel
    {
        return $this->buildQuery()->create($attributes);
    }
}
