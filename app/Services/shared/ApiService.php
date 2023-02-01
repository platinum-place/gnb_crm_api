<?php

namespace App\Services\shared;

use Illuminate\Support\Facades\Http;

abstract class ApiService
{
    protected array $config = [];

    protected array $header = [];

    protected function getResponsePostBodyAsform(string $url, array $body): array
    {
        $response = Http::asForm()->post($url, $body);
        return json_decode($response->body(), true);
    }

    protected function getResponseBody(string $url, array $body = [], array $header = []): array
    {
        $response = Http::withHeaders($header)->get($url, $body);
        return json_decode($response->body(), true);
    }

    protected function getResponseBodyPost(string $url, array $body = [], array $header = []): array
    {
        $response = Http::withHeaders($header)->post($url, $body);
        return json_decode($response->body(), true);
    }

    protected function arrayListMap(callable $callback, array $list)
    {
        return array_map($callback, array_keys($list), array_values($list));
    }
}
