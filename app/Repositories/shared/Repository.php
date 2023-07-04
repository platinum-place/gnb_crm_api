<?php

namespace App\Repositories\shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Repository implements IRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function list(array $params = []): LengthAwarePaginator
    {
        $builder = $this->model->newQuery();

        $columns = $this->filterColumns($params);
        $params = array_intersect_key($params, array_flip($columns));

        $builder = $this->filterBuilder($builder, $params);

        return $this->paginateBuilder($builder, $params);
    }

    private function filterColumns(array $params)
    {
        /**
         * Filter only column of the model table
         */
        $columns = $this->model->getConnection()->getSchemaBuilder()->getColumnListing($this->model->getTable());

        return array_intersect_key($params, array_flip($columns));
    }

    private function filterBuilder(Builder $builder, array $params = [])
    {
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $builder->orWhere(function ($query) use ($key, $value) {
                    foreach ($value as $singleValue) {
                        $query->orWhere($key, 'like', "%{$singleValue}%");
                    }
                });
            } else {
                $builder->where($key, 'like', "%{$value}%");
            }
        }

        return $builder;
    }

    private function paginateBuilder(Builder $builder, array $params = [])
    {
        $builder->orderBy(
            isset($params['order_by']) ? $params['order_by'] : 'id',
            isset($params['sort_by']) ? $params['sort_by'] : 'DESC',
        );

        return $builder->paginate(
            isset($params['per_page']) ? $params['per_page'] : 10,
            isset($params['page']) ? $params['page'] : 1,
        );
    }

    public function create(array $params): Model
    {
        return $this->model->create($params);
    }

    public function find(string $id): Model
    {
        return $this->model->find($id);
    }

    public function update(string $id, array $params): Model
    {
        return $this->model->find($id)->update($params);
    }

    public function delete(string $id): void
    {
        $this->model->find($id)->delete();
    }
}
