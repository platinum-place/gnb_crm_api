<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BuilderHelper
{
    public function filterBuilder(Builder $builder, array $params = []): LengthAwarePaginator
    {
        $model = $builder->getModel();

        /**
         * Filter only column of the model table
         */
        $fields = array_intersect_key($params, array_flip($model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable())));

        foreach ($fields as $key => $value) {
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

        $builder->orderBy(
            isset($params['order_by']) ? $params['order_by'] : 'id',
            isset($params['sort_by']) ? $params['sort_by'] : 'DESC',
        );

        return $builder->paginate(
            perPage: isset($params['per_page']) ? $params['per_page'] : 10,
            page: isset($params['page']) ? $params['page'] : 1,
        );
    }
}
