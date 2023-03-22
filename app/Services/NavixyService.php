<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiModel;

class NavixyService
{
    protected array $config = [], $header = [];

    protected ApiModel $model;

    public function __construct()
    {
        $this->config = config('navixy');
        $this->model = new ApiModel();
    }

    public function list()
    {
        $response = Http::get($this->config["url"] . "list", [
            'hash' => $this->config["hash"],
        ]);
        return json_decode($response->body(), true) ?? [];
    }

    public function find(int $id)
    {
        $response = Http::get($this->config["url"] . "get_last_gps_point", [
            'hash' => $this->config["hash"],
            'tracker_id' => $id,
        ]);

        $responseJson = json_decode($response->body(), true) ?? [];

        if (!empty($responseJson["lat"]))
            return $this->model->fill($responseJson);
    }

    public function getLocation(int $id)
    {
        $model = $this->find($id);
        return [
            "lat" => $model->lat,
            "lng" => $model->lng,
        ];
    }
}
