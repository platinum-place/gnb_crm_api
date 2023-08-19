<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SystrackHelper
{
    protected array $header = [];

    protected string $url = '';

    public function __construct()
    {
        $this->header = [
            'Accept' => 'application/json',
            'App-id' => config('systrack.app_id'),
            'User' => config('systrack.user'),
            'Pass' => config('systrack.pass'),
            'Authorization' => config('systrack.token'),
        ];

        $this->url = config('systrack')['url'];
    }

    public function list(int $id = null): array
    {
        return Http::withHeaders($this->header)->get($this->url.$id, [
            'FromIndex' => 0,
            'PageSize' => 1000,
        ])->json();
    }
}
