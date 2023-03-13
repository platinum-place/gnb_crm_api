<?php

namespace App\Repositories;

use App\Models\shared\ApiModel;

abstract class Repository implements IRepository
{
    protected static int $pagination = 10;

    protected ApiModel $model;

    public function __construct(ApiModel $model)
    {
        $this->model = $model;
    }
}
