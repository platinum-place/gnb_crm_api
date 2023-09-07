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
        ])->json();

        if (empty($response)) {
            throw new Exception(__('The Navixy API return a unknown error.'));
        }

        return collect($response['list'])->mapInto(ApiModel::class);
    }

    public function find(int $tracker_id)
    {
        $response = Http::get(config('navixy.url') . 'get_last_gps_point', [
            'hash' => config('navixy.hash'),
            'tracker_id' => $tracker_id,
        ])->json();

        if (empty($response)) {
            throw new Exception(__('The Navixy API return a unknown error.'));
        }

        return new ApiModel($response['value']);
    }
}
