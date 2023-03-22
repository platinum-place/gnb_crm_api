<?php

namespace App\Services;

use App\Models\ApiModel;
use App\Services\shared\ZohoBuilder;
use Illuminate\Support\Facades\Http;

class ZohoService
{
    protected array $config = [], $header = [];

    protected string $module = "";

    protected ApiModel $model;

    protected string $criteria = "";

    protected array $staticParams = ["page", "sort_by", "sort_order", "per_page"];

    protected array $operators = ["equals", "starts_with"];

    protected array $filters = [
        "per_page" => 10,
    ];

    public function __construct()
    {
        $this->config = config("zoho");
        $this->header = ['Authorization' => 'Zoho-oauthtoken ' . $this->generateToken()["access_token"]];
        $this->model = new ApiModel();
    }

    public function setModule(string $module)
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

    public function where($field, $operator = "", $value = "", $concatenator = "and")
    {
        if (!in_array($operator, $this->operators)) {
            $value = $operator;
            $operator = $this->operators[0];
        }

        if (in_array($field, $this->staticParams)) {
            $this->filters[$field] = $value;
        } else {
            $filter = "(($field:$operator:$value))";
            $filter .= (!empty($this->criteria)) ? $concatenator : "";
            $this->criteria .=  $filter;
        }

        return $this;
    }

    public function orWhere($field, $operator = "equals", $value)
    {
        return $this->where($field, $operator, $value, "or");
    }

    public function get()
    {
        $module = $this->module;

        if ((!empty($this->criteria))) {
            $module .= "/search";
            $this->filters = array_merge($this->filters, ["criteria" => $this->criteria]);
        }

        $response = $this->getRecords($module, $this->filters);

        if (!isset($response["data"]) or (isset($response["status"]) and $response["status"] == "error"))
            return collect(["data" => [],  "meta" => []]);

        $collection = collect($response["data"])->mapInto(get_class($this->model));

        return collect(["data" => $collection, "meta" => $response["info"]]);
    }

    public function find(int $id)
    {
        $response = $this->getRecord($this->module, $id);

        if (isset($response["status"]) and $response["status"] == "error" or empty($response))
            return null;

        return $this->model->fill($response["data"][0]);
    }
}
