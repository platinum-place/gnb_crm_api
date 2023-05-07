<?php

namespace App\Services;

use App\Models\shared\ApiModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class ZohoService
{
    protected array
        $config = [],
        $header = [],
        $params = ["page", "sort_by", "sort_order", "per_page"],
        $operators = ["equals", "starts_with"], $filters = [
            "per_page" => 10,
        ];

    protected string $module = "";

    public function __construct()
    {
        $this->config = config("zoho");
        $this->header = ['Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()["access_token"]];
    }

    public function prepare(string $module)
    {
        $this->module = $module;
        return $this;
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

    public function search($field, $operator = "", $value = "")
    {
        if (!in_array($operator, $this->operators)) {
            $value = $operator;
            $operator = $this->operators[0];
        }

        $filter = "(($field:$operator:$value))";

        if (in_array($field, $this->params))
            $this->filters[$field] = $value;
        else
            $this->filters['criteria'] = match (isset($this->filters['criteria'])) {
                true => $this->filters['criteria'] . " and $filter",
                false => $filter
            };

        return $this;
    }

    public function getRecords(): LengthAwarePaginator
    {
        $response = Http::withHeaders($this->header)->get($this->config["url_api"] . $this->module . "/search", $this->filters);
        $responseData = json_decode($response->body(), true);

        if (!isset($responseData["data"]) or (isset($responseData["status"]) and $responseData["status"] == "error"))
            throw new HttpResponseException(new JsonResponse(['message' => 'Records not found.'], 404));

        $collection = collect($responseData["data"])->mapInto(ApiModel::class);

        return new LengthAwarePaginator($collection, $responseData["info"]["count"], $responseData["info"]["per_page"], $responseData["info"]["page"], [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => [
                'sort_by' => $responseData["info"]["sort_by"],
                'sort_order' =>  $responseData["info"]["sort_order"],
            ]
        ]);
    }

    public function getRecord(string|int $id): object
    {
        $response = Http::withHeaders($this->header)->get($this->config["url_api"] . $this->module . "/$id");
        $responseData = json_decode($response->body(), true);

        if (!isset($responseData["data"]) or (isset($responseData["status"]) and $responseData["status"] == "error"))
            throw new HttpResponseException(new JsonResponse(['message' => 'Record not found.'], 404));

        return new ApiModel($responseData["data"][0]);
    }

    public function create(string $moduleName, array $body): object
    {
        $response = Http::withHeaders($this->header)->post($this->config["url_api"] . $moduleName, [
            "data" => [$body],
            "trigger" => ["approval", "workflow", "blueprint"],
        ]);
        $responseData = json_decode($response->body(), true);

        if (!isset($responseData["data"]) or (isset($responseData["status"]) and $responseData["status"] == "error"))
            throw new HttpResponseException(new JsonResponse(['message' => 'Server error.'], 500));

        return new ApiModel($responseData["data"][0]["details"]["id"]);
    }
}
