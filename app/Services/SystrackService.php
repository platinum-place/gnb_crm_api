<?php

namespace App\Services;

use App\Services\shared\ApiService;

class SystrackService extends ApiService
{
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
        return $this->getResponseBody(
            $this->config["url"] . $id,
            [
                'FromIndex' => 0,
                'PageSize' => 1000,
            ],
            $this->header
        );
    }
}
