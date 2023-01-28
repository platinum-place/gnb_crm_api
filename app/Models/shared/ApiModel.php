<?php

namespace App\Models\shared;

abstract class ApiModel
{
    protected array $attributes = [];

    protected array $fillable = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    protected function fill(array $attributes)
    {
        $fillable = array_intersect_key($attributes, array_flip($this->getFillable()));
        foreach ($fillable as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __set($name, $value)
    {
        // mutate function
        $this->attributes[$name] = $value;
    }

    protected function isFillable($key)
    {
        if (in_array($key, $this->getFillable())) {
            return true;
        }
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
}
