<?php

namespace App\Services\shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected array $paginateFields = ['order_by', 'sort_by', 'per_page', 'page'];

    private function filterBuilder(Builder $builder, array $params = []): Builder
    {
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $builder->orWhere(function ($query) use ($key, $value) {
                    foreach ($value as $singleValue) {
                        $query->where($key, 'like', "%{$singleValue}%");
                    }
                });
            } else {
                $builder->where($key, 'like', "%{$value}%");
            }
        }

        return $builder;
    }

    private function paginateBuilder(
        Builder $builder,
        string $order_by = 'id',
        string $sort_by = 'DESC',
        int $per_page = 10,
        int $page = null
    ): LengthAwarePaginator {
        $builder->orderBy($order_by, strtoupper($sort_by));

        return $builder->paginate($per_page, page: $page);
    }

    private function buildList(array $params): Builder
    {
        $builder = $this->model->newQuery();

        /**
         * Filter only column of the model table
         */
        $columns = $this->model->getColumnNames();
        $fields = array_intersect_key($params, array_flip($columns));

        return $this->filterBuilder($builder, $fields);
    }

    private function paginateList(Builder $list, array $params): LengthAwarePaginator
    {
        $paginateParams = array_intersect_key($params, array_flip($this->paginateFields));

        return $this->paginateBuilder($list, ...$paginateParams);
    }

    public function list(array $params = []): LengthAwarePaginator
    {
        $list = $this->buildList($params);
        $list = $this->paginateList($list, $params);

        return $list;
    }

    public function create(array $params = []): Model
    {
        return $this->model->create($params);
    }

    public function getBy(string $id, string $field = 'id'): Model
    {
        return $this->model->firstWhere($field, $id);
    }

    public function update(string $id, array $params = []): Model
    {
        $model = $this->getBy($id);

        $model->update($params);
        $model->fresh();

        return $model;
    }

    public function delete(string $id): bool|null
    {
        $model = $this->getBy($id);

        return $model->delete();
    }
}
