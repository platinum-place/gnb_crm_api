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

    public function fill(array $attributes)
    {
        if (!empty($this->fillable))
            $attributes = array_intersect_key($attributes, array_flip($this->fillable));

        foreach ($attributes as $key => $value)
            if (empty($this->fillable) or $this->isFillable($key))
                $this->setAttribute($key, $value);

        return $this;
    }

    public function __get($name)
    {
        return method_exists($this, $name) ?
            $this->$name() : (property_exists(static::class, $name) ?
                static::${$name} : (property_exists($this, $name) ?
                    $this->{$name} : (is_array($this->attributes[$name]) ?
                        (object) $this->attributes[$name] : $this->attributes[$name])));
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

    protected function isFillable($key)
    {
        return in_array($key, $this->fillable);
    }
}
