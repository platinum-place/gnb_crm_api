<?php

namespace App\Models\shared;

abstract class ApiModel
{
    protected array $attributes = [];

    protected array $fillable = [];

    public function __construct(array $attributes = [])
    {
        if (!empty($attributes))
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
        return method_exists($this, $name) ? $this->$name() : (property_exists(static::class, $name) ? static::${$name} : (property_exists($this, $name) ? $this->{$name} : $this->attributes[$name]));
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
}
