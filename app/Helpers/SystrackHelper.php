<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SystrackHelper
{
    public function list(int $id = null): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'App-id' => env('SYSTRACK_APP_ID'),
            'User' => env('SYSTRACK_USER'),
            'Pass' => env('SYSTRACK_PASS'),
            'Authorization' => env('SYSTRACK_AUTHORIZATION'),
        ])->get(env('SYSTRACK_URL').$id, [
            'FromIndex' => 0,
            'PageSize' => 1000,
        ]);

        $responseJson = $response->json();

        if (empty($responseJson['trackPoint'])) {
            throw new \Exception(__('Error, location empty.'), 501);
        }

        return $responseJson;
    }

    public function getLocation(int $id)
    {
        $location = $this->list($id);

        return [
            'lat' => $location['trackPoint']['position']['latitude'],
            'lng' => $location['trackPoint']['position']['longitude'],
        ];
    }
}
