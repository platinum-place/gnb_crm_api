<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class NavixyHelper
{
    public function list()
    {
        Http::get(env('NAVIXY_URL').'list', ['hash' => env('hash')])->json();
    }

    public function find(int $id)
    {
        $response = Http::get(env('NAVIXY_URL').'get_last_gps_point', [
            'hash' => env('NAVIXY_HASH'),
            'tracker_id' => $id,
        ]);

        $responseJson = $response->json();

        if (empty($responseJson['lat'])) {
            throw new \Exception(__('Error, location empty.'), 501);
        }

        return $responseJson;
    }

    public function getLocation(int $id)
    {
        $model = $this->find($id);

        return [
            'lat' => $model['lat'],
            'lng' => $model['lng'],
        ];
    }
}
