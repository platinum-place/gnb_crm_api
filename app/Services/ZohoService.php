<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZohoService
{
    protected static array $config;

    public function __construct()
    {
        self::$config = config('zoho');
    }

    public static function generateRefreshToken(): string
    {
        $response = Http::asForm()->post(self::$config["url_token"], [
            "client_id" => self::$config["client_id"],
            "client_secret" => self::$config["client_secret"],
            "redirect_uri" => self::$config["redirect_uri"],
            "grant_type" => self::$config["grant_type_generate"],
            "code" => self::$config["grant_token"],
        ]);
        return json_decode($response->body(), true)["refresh_token"];
    }

    public static function generateAccessToken(): string
    {
        $response = Http::asForm()->post(self::$config["url_token"], [
            "client_id" => self::$config["client_id"],
            "client_secret" => self::$config["client_secret"],
            "grant_type" => self::$config["grant_type"],
            "refresh_token" => self::$config["refresh_token"],
        ]);
        return json_decode($response->body(), true)["access_token"];
    }

    public static function getRecords(
        string $token,
        string $moduleName,
        int $page = 1,
        int $per_page = 10,
        string $sort_order = "desc",
        /** ["id", "Created_Time", "Modified_Time"] */
        string $sort_by = "id",
    ): array {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $token,
        ])->get(self::$config["url_api"] . $moduleName, [
            "page" => $page,
            "per_page" => $per_page,
            "sort_order" => $sort_order,
            "sort_by" => $sort_by,
        ]);
        return json_decode($response->body(), true);
    }
}
