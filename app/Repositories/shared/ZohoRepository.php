<?php

namespace App\Repositories\shared;

use App\Facades\Zoho;
use App\Repositories\shared\IApiRepository;

abstract class ZohoRepository implements IApiRepository
{
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
