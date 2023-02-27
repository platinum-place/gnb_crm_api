<?php

namespace App\Services;

use App\Services\shared\ApiService;

class ZohoService extends ApiService
{
    // total access scope ZohoCRM.settings.ALL, ZohoCRM.modules.ALL,
    public function __construct()
    {
        $this->config = config("zoho");

        $this->header = [
            'Authorization' => 'Zoho-oauthtoken ' . $this->createAccessToken()
        ];
    }

    public function createAccessToken(): string
    {
        $response = $this->getResponsePostBodyAsform($this->config["url_token"], [
            "client_id" => $this->config["client_id"],
            "client_secret" => $this->config["client_secret"],
            "grant_type" => $this->config["grant_type"],
            "refresh_token" => $this->config["refresh_token"],
        ]);
        return $response["access_token"];
    }

    public function getRecords(string $moduleName, array $params = []): array
    {
        return $this->getResponseBody(
            $this->config["url_api"] . $moduleName,
            $params,
            $this->header
        );
    }

    public function getRecord(string $moduleName, string $id): array
    {
        return $this->getResponseBody(
            $this->config["url_api"] . $moduleName . "/" . $id,
            header: $this->header
        );
    }

    public function create(string $moduleName, array $body): array
    {
        return $this->getResponseBodyPost(
            $this->config["url_api"] . $moduleName,
            [
                "data" => [$body],
                "trigger" => ["approval", "workflow", "blueprint"],
            ],
            $this->header
        );
    }
}
