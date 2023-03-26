<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZohoService
{
    protected array $config = [], $header = [];

    public function __construct()
    {
        $this->config = config("zoho");
        $this->header = ['Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()["access_token"]];
    }

    public function generateToken(): array
    {
        $response = Http::asForm()->post($this->config["url_token"], [
            "client_id" => $this->config["client_id"],
            "client_secret" => $this->config["client_secret"],
            "grant_type" => "refresh_token",
            "refresh_token" => $this->config["refresh_token"],
        ]);
        return json_decode($response->body(), true);
    }

    // total access scope ZohoCRM.settings.ALL, ZohoCRM.modules.ALL,
    public function generatePersistentToken(string $code): array
    {
        $response = Http::asForm()->post($this->config["url_token"], [
            "client_id" => $this->config["client_id"],
            "client_secret" => $this->config["client_secret"],
            "grant_type" => "authorization_code",
            "redirect_uri" => $this->config["redirect_uri"],
            "code" => $code,
        ]);
        return json_decode($response->body(), true);
    }

    // sort_by = ["id", "Created_Time", "Modified_Time"]
    // int $page = 1,
    // int $per_page = 10,
    // string $sort_order = "desc",
    // string $sort_by = "id"
    public function getRecords(string $moduleName, array $body = []): array
    {
        $response = Http::withHeaders($this->header)->get($this->config["url_api"] . $moduleName, $body);
        return json_decode($response->body(), true) ?? [];
    }

    public function getRecord(string $moduleName, string $id): array
    {
        $response = Http::withHeaders($this->header)->get($this->config["url_api"] . $moduleName . "/" . $id);
        return json_decode($response->body(), true) ?? [];
    }

    public function create(string $moduleName, array $body): array
    {
        $response = Http::withHeaders($this->header)->post($this->config["url_api"] . $moduleName, [
            "data" => [$body],
            "trigger" => ["approval", "workflow", "blueprint"],
        ]);
        return json_decode($response->body(), true);
    }
}
