<?php

namespace App\Services;

use App\Services\shared\ApiService;

class ZohoService extends ApiService
{
    public function __construct()
    {
        $this->config = config('zoho');

        $this->header = [
            'Authorization' => 'Zoho-oauthtoken ' . $this->generateAccessToken()["access_token"]
        ];
    }

    public function generateRefreshToken(): array
    {
        return $this->getResponsePostBodyAsform($this->config["url_token"], [
            "client_id" => $this->config["client_id"],
            "client_secret" => $this->config["client_secret"],
            "redirect_uri" => $this->config["redirect_uri"],
            "grant_type" => $this->config["grant_type_generate"],
            "code" => $this->config["grant_token"],
        ]);
    }

    public function generateAccessToken(): array
    {
        return $this->getResponsePostBodyAsform($this->config["url_token"], [
            "client_id" => $this->config["client_id"],
            "client_secret" => $this->config["client_secret"],
            "grant_type" => $this->config["grant_type"],
            "refresh_token" => $this->config["refresh_token"],
        ]);
    }

    /** sort_by = ["id", "Created_Time", "Modified_Time"] */
    public function getRecords(string $moduleName, int $page = 1, int $per_page = 10, string $sort_order = "desc", string $sort_by = "id"): array
    {
        return $this->getResponseBody(
            $this->config["url_api"] . $moduleName,
            [
                "page" => $page,
                "per_page" => $per_page,
                "sort_order" => $sort_order,
                "sort_by" => $sort_by,
            ],
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
