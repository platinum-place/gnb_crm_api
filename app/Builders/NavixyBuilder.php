<?php

namespace App\Builders;

use App\Models\shared\Navixy;
use App\Services\NavixyService;

class NavixyBuilder
{
    protected Navixy $model;

    public function getModel()
    {
        return $this->model;
    }

    public function setModel(Navixy $model)
    {
        $this->model = $model;
        return $this;
    }

    public function list()
    {
        $response = (new NavixyService)->list();
        return collect($response["list"])->mapInto(get_class($this->model));
    }

    public function find(int $id)
    {
        $response = (new NavixyService)->find($id);
        return $this->model->fill($response["value"]);
    }
}
