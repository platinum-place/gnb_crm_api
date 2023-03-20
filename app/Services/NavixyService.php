<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NavixyService
{
    protected array $config = [], $header = [];

    public function __construct()
    {
        $this->config = config('navixy');
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
        return json_decode($response->body(), true) ?? [];
    }
}
