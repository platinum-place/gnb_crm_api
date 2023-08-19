<?php

namespace App\Services\shared;

use App\Facades\Navixy;
use App\Models\shared\ApiModel;

trait NavixyApi
{
    public function navixylist()
    {
        $response = Navixy::list();

        return collect($response['list'])->mapInto(ApiModel::class);
    }

    public function findByNavixyId(int $id): ApiModel
    {
        $response = Navixy::find($id);

        return new ApiModel($response['value']);
    }
}
