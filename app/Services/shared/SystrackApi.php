<?php

namespace App\Services\shared;

use App\Facades\Systrack;
use App\Models\shared\ApiModel;

trait SystrackApi
{
    public function systracklist()
    {
        return collect(Systrack::list())->mapInto(ApiModel::class);
    }

    public function findBySystrackId(int $id): ApiModel
    {
        return new ApiModel(Systrack::list($id));
    }
}
