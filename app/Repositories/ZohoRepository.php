<?php

namespace App\Repositories;

use App\Facades\Zoho;
use App\Repositories\shared\IApiRepository;

class ZohoRepository implements IApiRepository
{
    protected string $module;

    public function __construct(string $module)
    {
        $this->module = $module;
    }

    public function list(array $params)
    {
        $query = Zoho::prepare($this->module);
        foreach ($params as $key => $value) {
            $query->search($key, $value);
        }
        return $query->getRecords();
    }

    public function getById(string|int $id)
    {
        return Zoho::prepare($this->module)->getRecord($id);
    }

    public function create(array $attributes)
    {
        $case = Zoho::prepare($this->module)->create(array_merge($attributes, [
            "Status" => "Ubicado",
            "Caso_especial" => true,
            "Aseguradora" => auth()->user()->account_name,
            "Related_To" => auth()->user()->contact_name_id,
            "Subject" => "Asistencia remota",
            "Case_Origin" => "API",
        ]));
        return $this->getById($case->id);
    }
}
