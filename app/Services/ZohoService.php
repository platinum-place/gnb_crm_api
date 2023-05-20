<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class ZohoService
{
    protected array
        $config = [],
        $header = [];

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

    // total access scope: ZohoCRM.settings.ALL, ZohoCRM.modules.ALL,
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

    public function getRecords(string $moduleName, array $filters): array
    {
        $response = Http::withHeaders($this->header)->get($this->config["url_api"] . $moduleName . "/search", $filters);
        $responseData = json_decode($response->body(), true);

        if (!isset($responseData["data"]) or (isset($responseData["status"]) and $responseData["status"] == "error"))
            throw new \App\Exceptions\ZohoHttpException($responseData, 'Records not found.');


        return $responseData;
    }

    public function getRecord(string $moduleName, int|string $id): array
    {
        $response = Http::withHeaders($this->header)->get($this->config["url_api"] . $moduleName . "/$id");
        $responseData = json_decode($response->body(), true);

        if (!isset($responseData["data"]) or (isset($responseData["status"]) and $responseData["status"] == "error"))
            throw new \App\Exceptions\ZohoHttpException($responseData, 'Record not found.');

        return $responseData["data"][0];
    }

    public function create(string $moduleName, array $body): int|string
    {
        $response = Http::withHeaders($this->header)->post($this->config["url_api"] . $moduleName, [
            "data" => [$body],
            "trigger" => ["approval", "workflow", "blueprint"],
        ]);
        $responseData = json_decode($response->body(), true);

        if (!isset($responseData["data"]) or (isset($responseData["status"]) and $responseData["status"] == "error"))
            throw new \App\Exceptions\ZohoHttpException($responseData, 'Server error.');

        return $responseData["data"][0]["details"]["id"];
    }
}
