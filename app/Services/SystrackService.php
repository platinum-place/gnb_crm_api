<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiModel;
use App\Services\shared\SystrackBuilder;

class SystrackService
{
    protected array $config = [], $header = [];

    public function __construct()
    {
        $this->config = config('systrack');

        $this->header =  [
            "Accept" => "application/json",
            "App-id" => $this->config["app_id"],
            "User" => $this->config["user"],
            "Pass" => $this->config["pass"],
            "Authorization" => $this->config["token"],
        ];
    }

    public function list(int $id = null)
    {
        $response = Http::withHeaders($this->header)->get($this->config["url"] . $id, [
            'FromIndex' => 0,
            'PageSize' => 1000,
        ]);

        $responseJson = json_decode($response->body(), true) ?? [];

        if (!empty($responseJson["trackPoint"]))
            return $responseJson;
    }

    public function getLocation(int $id)
    {
        $model = $this->list($id);
        return [
            "lat" => $model->trackPoint["position"]["latitude"],
            "lng" => $model->trackPoint["position"]["longitude"],
        ];
    }
}
