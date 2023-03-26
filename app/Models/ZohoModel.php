<?php

namespace App\Models;

use App\Builders\ZohoBuilder;
use App\Models\shared\ApiModel;

class ZohoModel extends ApiModel
{
    public string $module = "";

    // translate the values sent by the client
    protected array $mutable = [];

    public function newQuery()
    {
        return new ZohoBuilder($this);
    }

    protected function setMutation(array $attributes)
    {
        if (!empty($this->mutable)) {
            $attributesMutated = [];
            foreach ($this->mutable as $key => $value) {
                if (array_key_exists($value, $attributes)) {
                    $attributesMutated[$key] = $attributes[$value];
                }
            }

            if (!empty($attributesMutated)) {
                $attributes =  array_merge($attributesMutated, $attributes);
                foreach ($this->mutable as $key => $value) {
                    if (isset($attributes[$value]) && !is_null($value)) {
                        $attributes[$value] = $attributes[$value];
                        unset($attributes[$value]);
                    }
                }
            }
        }
        return $attributes;
    }

    public function where($field, $operator = "", $value = "", $concatenator = "and")
    {
        return $this->newQuery()->where($field, $operator, $value, $concatenator);
    }

    public function find(int $id)
    {
        return $this->newQuery()->find($id);
    }

    public function create(array $attributes)
    {
        $attributes = (empty($attributes)) ? $this->attributes : $attributes;

        $attributes = $this->setMutation($attributes);

        return $this->newQuery()->create($attributes);
    }
}
