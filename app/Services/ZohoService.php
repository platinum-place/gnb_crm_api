<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZohoService
{
    protected static array $config;

    protected static string $token;

    public function __construct()
    {
        self::$config = config('zoho');
        self::$token = self::generateAccessToken();
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

    /** sort_by = ["id", "Created_Time", "Modified_Time"] */
    public static function getRecords(string $moduleName, int $page = 1, int $per_page = 10, string $sort_order = "desc", string $sort_by = "id",): array
    {
        $response = Http::withHeaders(['Authorization' => 'Zoho-oauthtoken ' . self::$token])
            ->get(self::$config["url_api"] . $moduleName, [
                "page" => $page,
                "per_page" => $per_page,
                "sort_order" => $sort_order,
                "sort_by" => $sort_by,
            ]);
        return json_decode($response->body(), true);
    }

    public static function getRecord(string $moduleName, string $id): array
    {
        $response = Http::withHeaders(['Authorization' => 'Zoho-oauthtoken ' . self::$token])
            ->get(self::$config["url_api"] . $moduleName . "/" . $id);
        return json_decode($response->body(), true);
    }

    public static function create(string $moduleName, array $body): array
    {
        $response = Http::withHeaders(['Authorization' => 'Zoho-oauthtoken ' . self::$token])
            ->post(self::$config["url_api"] . $moduleName, $body);
        return json_decode($response->body(), true);
    }
}
