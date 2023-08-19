<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ZohoHelper
{
    protected array $header = [];

    protected string $url = '';

    public function __construct()
    {
        $this->url = config('zoho.url');
        $this->header = ['Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()['access_token']];
    }

    public function generateToken(): array
    {
        return Http::asForm()->post(config('zoho.url_token'), [
            'client_id' => config('zoho.client_id'),
            'client_secret' => config('zoho.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => config('zoho.refresh_token'),
        ])->json();
    }

    // total access scope: ZohoCRM.settings.ALL, ZohoCRM.modules.ALL,
    public function generatePersistentToken(string $code): array
    {
        return Http::asForm()->post(config('zoho')['url_token'], [
            'client_id' => config('zoho.client_id'),
            'client_secret' => config('zoho.client_secret'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('zoho.redirect_uri'),
            'code' => $code,
        ])->json();
    }

    public function getRecords(string $moduleAPIName, string $uri = "", array $body = [])
    {
        return Http::withHeaders($this->header)->get($this->url . $moduleAPIName . "/$uri", $body)->json();
    }

    public function getRecord(string $moduleAPIName, string $uri): array
    {
        return Http::withHeaders($this->header)->get($this->url . $moduleAPIName . "/$uri")->json();
    }

    public function create(string $moduleAPIName, array $body): array
    {
        return Http::withHeaders($this->header)->post($this->url . $moduleAPIName, [
            'data' => [$body],
            'trigger' => ['approval', 'workflow', 'blueprint'],
        ])->json();
    }
}
