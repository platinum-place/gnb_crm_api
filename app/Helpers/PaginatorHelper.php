<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class PaginatorHelper
{
    protected Builder $builder;

    protected string $order_by = 'id';

    protected string $sort_by = 'DESC';

    protected int $per_page = 10;

    protected int $page = 1;

    public function setModel(string $model)
    {
        $this->builder = (new $model)->newQuery();

        return $this;
    }

    public function filter(array $params = [])
    {
        $fields = array_intersect_key($params, array_flip(['order_by', 'sort_by', 'per_page', 'page']));

        if (isset($fields['order_by'])) {
            $this->order_by = $fields['order_by'];
        }

        if (isset($fields['sort_by'])) {
            $this->sort_by = $fields['sort_by'];
        }

        if (isset($fields['per_page'])) {
            $this->per_page = $fields['per_page'];
        }

        if (isset($fields['page'])) {
            $this->page = $fields['page'];
        }

        $model = $this->builder->getModel();

        /**
         * Filter only column of the model table
         */
        $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());

        $params = array_intersect_key($params, array_flip($columns));

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $this->builder->orWhere(function ($query) use ($key, $value) {
                    foreach ($value as $singleValue) {
                        $query->orWhere($key, 'like', "%{$singleValue}%");
                    }
                });
            } else {
                $this->builder->where($key, 'like', "%{$value}%");
            }
        }

        return $this;
    }

    public function get(
        string $order_by = null,
        string $sort_by = null,
        int $per_page = null,
        int $page = null
    ) {
        $this->builder->orderBy($order_by ?? $this->order_by, $sort_by ?? $this->sort_by);

        return $this->builder->paginate($per_page ?? $this->per_page, page: $page ?? $this->page);
    }
}
