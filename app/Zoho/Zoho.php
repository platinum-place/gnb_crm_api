<?php

namespace App\Zoho;

use Illuminate\Support\Facades\Http;

abstract class Zoho
{
    protected array $header = [];

    protected array $params = ['order_by', 'sort_by', 'per_page', 'page'];

    public function __construct()
    {
        $this->header = ['Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()['access_token']];
    }

    public function generateToken(): array
    {
        return Http::asForm()->post(env('ZOHO_URL_TOKEN'), [
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'grant_type' => 'refresh_token',
            'refresh_token' => env('ZOHO_REFRESH_TOKEN'),
        ])->json();
    }

    // total access scope: ZohoCRM.settings.ALL, ZohoCRM.modules.ALL,
    public function generatePersistentToken(string $code): array
    {
        return Http::asForm()->post(env('ZOHO_URL_TOKEN'), [
            'client_id' => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => env('ZOHO_REDIRECT_URI'),
            'code' => $code,
        ])->json();
    }

    public function getAll(string $moduleAPIName, string $uri, array $body)
    {
        $url = env('ZOHO_URL_API') . $moduleAPIName . $uri;
        $header = ['Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()['access_token']];
        return Http::withHeaders($header)->get($url, $body)->json();
    }

    public function get(string $moduleAPIName, string $uri): array
    {
        $url = env('ZOHO_URL_API') . $moduleAPIName . $uri;
        $header = ['Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()['access_token']];
        return Http::withHeaders($header)->get($url)->json();
    }
}
