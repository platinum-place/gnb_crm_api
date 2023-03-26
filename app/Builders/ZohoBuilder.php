<?php

namespace App\Builders;

use App\Models\ZohoModel;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Facades\Zoho;

class ZohoBuilder
{
    protected ZohoModel $model;

    protected string $criteria = "";

    protected array $staticParams = ["page", "sort_by", "sort_order", "per_page"];

    protected array $operators = ["equals", "starts_with"];

    protected array $filters = [
        "per_page" => 10,
    ];

    public function __construct(ZohoModel $model)
    {
        $this->model = $model;
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

    public function get()
    {
        $module = $this->model->module;

        if ((!empty($this->criteria))) {
            $module .= "/search";
            $this->filters = array_merge($this->filters, ["criteria" => $this->criteria]);
        }

        $response = Zoho::getRecords($module, $this->filters);

        if (!isset($response["data"]) or (isset($response["status"]) and $response["status"] == "error"))
            return [];

        $collection = collect($response["data"])->mapInto(get_class($this->model));

        return new LengthAwarePaginator($collection, $response["info"]["count"], $response["info"]["per_page"], $response["info"]["page"], [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => [
                'sort_by' => $response["info"]["sort_by"],
                'sort_order' =>  $response["info"]["sort_order"],
            ]
        ]);
    }

    public function find(int $id)
    {
        $response = Zoho::getRecord($this->model->module, $id);

        if (isset($response["status"]) and $response["status"] == "error" or empty($response))
            return null;

        return $this->model->fill($response["data"][0]);
    }

    public function create(array $attributes)
    {
        $response = Zoho::create($this->model->module, $attributes);

        if (isset($response["data"][0]["details"]["id"]))
            return $this->find($response["data"][0]["details"]["id"]);

        return null;
    }
}
