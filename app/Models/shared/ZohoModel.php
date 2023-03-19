<?php

namespace App\Models\shared;

use App\Facades\Zoho;

abstract class ZohoModel extends ApiModel
{
    protected string $module = '';

    protected array $filters = [
        "per_page" => 10,
    ];

    protected string $criteria = "";

    protected array $staticParams = ["page", "sort_by", "sort_order", "per_page"];

    protected array $operators = ["equals", "starts_with"];

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

        $response = Zoho::getRecords($module, $this->filters);

        if (!isset($response["data"]) or (isset($response["status"]) and $response["status"] == "error"))
            return collect(["data" => [],  "meta" => []]);

        $collection = collect($response["data"])->mapInto(get_called_class());

        return collect(["data" => $collection, "meta" => $response["info"]]);
    }

    public function find(int $id)
    {
        $response = Zoho::getRecord($this->module, $id);

        if (isset($response["status"]) and $response["status"] == "error" or empty($response))
            return null;

        $this->fill($response["data"][0]);

        return $this;
    }
}
