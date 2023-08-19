<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class NavixyHelper
{
    protected string $url = '';

    public function __construct()
    {
        $this->url = config('navixy.url');
    }

    public function list(): array
    {
        return Http::get($this->url.'list', [
            'hash' => config('navixy.hash'),
        ])->json();
    }

    public function find(int $tracker_id)
    {
        return Http::get($this->url.'get_last_gps_point', [
            'hash' => config('navixy.hash'),
            'tracker_id' => $tracker_id,
        ])->json();
    }
}
