<?php

namespace App\Builders;

use App\Models\shared\Systrack;
use App\Services\SystrackService;

class SystrackBuilder
{
    protected Systrack $model;

    public function getModel()
    {
        return $this->model;
    }

    public function setModel(Systrack $model)
    {
        $this->model = $model;
        return $this;
    }

    public function list()
    {
        $response = (new SystrackService)->list();
        return collect($response)->mapInto(get_class($this->model));
    }

    public function find(int $id)
    {
        $response = (new SystrackService)->list($id);
        return $this->model->fill($response);
    }
}
