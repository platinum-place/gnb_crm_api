<?php

namespace App\Services;

use App\Services\shared\ApiService;

class NavixyService extends ApiService
{
    public function __construct()
    {
        $this->config = config('navixy');
    }

    public function list()
    {
        return $this->getResponseBody(
            $this->config["url"] . "list",
            [
                'hash' => $this->config["hash"],
            ],
        );
    }

    public function find(int $id)
    {
        return $this->getResponseBody(
            $this->config["url"] . "get_last_gps_point",
            [
                'hash' => $this->config["hash"],
                'tracker_id' => $id,
            ],
        );
    }
}
