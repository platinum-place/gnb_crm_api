<?php

namespace App\Builders;

use App\Facades\Zoho;
use App\Models\shared\ZohoModel;
use Illuminate\Pagination\LengthAwarePaginator;

class ZohoBuilder
{
    protected array
    $params = ["page", "sort_by", "sort_order", "per_page"],
    $operators = ["equals", "starts_with"],
    $filters = [];

    protected string $module = "";

    protected ZohoModel $model;

    public function __construct(string $module, ZohoModel $model)
    {
        $this->module = $module;
        $this->model = $model;
    }

    public function where($field, $operator = "", $value = "")
    {
        if (!in_array($operator, $this->operators)) {
            $value = $operator;
            $operator = $this->operators[0];
        }

        if (!in_array($field, $this->params)) {
            $filter = "(($field:$operator:$value))";

            $this->filters['criteria'] = match (isset ($this->filters['criteria'])) {
                true => $this->filters['criteria'] . " and $filter",
                false => $filter
            };
        }

        return $this;
    }

    public function get(int $per_page = null, int $page = null, string $sort_by = null, int $sort_order = null): LengthAwarePaginator
    {
        if ($per_page)
            $this->filters['per_page'] = $per_page;

        if ($page)
            $this->filters['page'] = $page;

        if ($sort_by)
            $this->filters['sort_by'] = $sort_by;

        if ($sort_order)
            $this->filters['sort_order'] = $sort_order;

        $data = Zoho::getRecords($this->module, $this->filters);

        $collection = collect($data["data"])->mapInto(get_class($this->model));

        return new LengthAwarePaginator($collection, $data["info"]["count"], $data["info"]["per_page"], $data["info"]["page"], [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => [
                'sort_by' => $data["info"]["sort_by"],
                'sort_order' => $data["info"]["sort_order"],
            ]
        ]);
    }

    public function find(string|int $id)
    {
        $data = Zoho::getRecord($this->module, $id);
        return $this->model->fill($data);
    }

    public function create(array $attributes)
    {
        $id = Zoho::create($this->module, $attributes);
        return $this->find($id);
    }
}