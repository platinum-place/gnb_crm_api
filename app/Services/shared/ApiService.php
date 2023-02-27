<?php

namespace App\Services\shared;

use Illuminate\Support\Facades\Http;

abstract class ApiService
{
    protected array $config = [];

    protected array $header = [];

    private function responseArray($response): array
    {
        try {
            return json_decode($response->body(), true);
        } catch (\Throwable $th) {
            //throw $th;
            return [];
        }
    }

    protected function getResponsePostBodyAsform(string $url, array $body)
    {
        $response = Http::asForm()->post($url, $body);
        return $this->responseArray($response);
    }

    protected function getResponseBody(string $url, array $body = [], array $header = [])
    {
        $response = Http::withHeaders($header)->get($url, $body);
        return $this->responseArray($response);
    }

    protected function getResponseBodyPost(string $url, array $body = [], array $header = [])
    {
        $response = Http::withHeaders($header)->post($url, $body);
        return $this->responseArray($response);
    }

    protected function arrayListMap(callable $callback, array $list)
    {
        return array_map($callback, array_keys($list), array_values($list));
    }
}
