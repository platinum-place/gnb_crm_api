<?php

namespace App\Models\shared;

abstract class ApiModel
{
    protected array $attributes = [];

    protected array $fillable = [];

    // translate the values sent by the client
    protected array $mutable = [];

    public function __construct(array $attributes = [])
    {
        if (!empty($attributes))
            $this->fill($attributes);
    }

    public function fill(array $attributes)
    {
        $attributes = array_intersect_key($attributes, array_flip($this->fillable));

        foreach ($attributes as $key => $value)
            if ($this->isFillable($key))
                $this->setAttribute($key, $value);

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

    protected function isFillable($key)
    {
        return in_array($key, $this->fillable);
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
}
