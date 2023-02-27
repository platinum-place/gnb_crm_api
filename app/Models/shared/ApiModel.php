<?php

namespace App\Models\shared;

abstract class ApiModel
{
    protected array $attributes = [];

    protected array $fillable = [];

    protected array $mutate = [];

    public function __construct(array $attributes = [])
    {
        if (!empty($attributes))
            $this->fill($attributes);
    }

    protected function fill(array $attributes)
    {
        $attributes = $this->setMutation($attributes);

        $fillable = array_intersect_key($attributes, array_flip($this->getFillable()));

        foreach ($fillable as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
    }

    public function __get($name)
    {
        return match (method_exists($this, $name)) {
            true => $this->{$name}(),
            false =>  $this->attributes[$name],
        };
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    protected function isFillable($key)
    {
        return (in_array($key, $this->getFillable())) ? true : false;
    }

    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    protected function getFillable()
    {
        return $this->fillable;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    protected function getMutation()
    {
        return $this->mutate;
    }

    protected function setMutation(array $attributes)
    {
        if (!empty($attributes) and $this->isMutateble()) {
            $attributesMutated = [];
            foreach ($this->getMutation() as $key => $value) {
                if (array_key_exists($value, $attributes)) {
                    $attributesMutated[$key] = $attributes[$value];
                }
            }
            if (!empty($attributesMutated))
                $attributes = array_unique(array_merge($attributesMutated, $attributes));
        }
        return $attributes;
    }

    protected function isMutateble()
    {
        return (count($this->getMutation()) > 0) ? true : false;
    }
}
