<?php

namespace App\Helpers;

use Exception;
use App\Facades\Systrack;
use App\Models\shared\ApiModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class SystrackHelper
{
    public function list(int $id = null): ApiModel|Collection
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'App-id' => config('systrack.app_id'),
            'User' => config('systrack.user'),
            'Pass' => config('systrack.pass'),
            'Authorization' => config('systrack.token'),
        ])
            ->get(config('systrack.url') . $id, [
                'FromIndex' => 0,
                'PageSize' => 1000,
            ])
            ->json();

        if (empty($response)) {
            throw new Exception(__('The Systrack API return a unknown error.'));
        }

        if ($id) {
            return new ApiModel($response);
        } else {
            return collect($response)->mapInto(ApiModel::class);
        }
    }
}
