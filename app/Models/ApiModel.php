<?php

namespace App\Models;

class ApiModel
{
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        if (!empty($attributes))
            $this->fill($attributes);
    }

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function __get($name)
    {
        return method_exists($this, $name) ? $this->$name() : (property_exists(static::class, $name) ? static::${$name} : (property_exists($this, $name) ? $this->{$name} : $this->attributes[$name]));
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }
}
