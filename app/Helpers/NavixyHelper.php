<?php

namespace App\Helpers;

use Exception;
use App\Models\shared\ApiModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class NavixyHelper
{
    public function list(): Collection
    {
        $response = Http::get(config('navixy.url') . 'list', [
            'hash' => config('navixy.hash'),
        ])
        ->throw()
        ->json();

        return collect($response['list'])->mapInto(ApiModel::class);
    }

    public function find(int $tracker_id)
    {
        $response = Http::get(config('navixy.url') . 'get_last_gps_point', [
            'hash' => config('navixy.hash'),
            'tracker_id' => $tracker_id,
        ])
        ->throw()
        ->json();

        return new ApiModel($response['value']);
    }
}
