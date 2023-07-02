<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NavixyHelper
{
    protected array $config = [];

    protected array $header = [];

    public function __construct()
    {
        $this->config = config('navixy');
    }

    public function list()
    {
        $response = Http::get($this->config['url'].'list', [
            'hash' => $this->config['hash'],
        ]);

        return json_decode($response->body(), true);
    }

    public function find(int $id)
    {
        $response = Http::get($this->config['url'].'get_last_gps_point', [
            'hash' => $this->config['hash'],
            'tracker_id' => $id,
        ]);

        $responseJson = json_decode($response->body(), true);

        if (! empty($responseJson['lat'])) {
            return $responseJson;
        }
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
