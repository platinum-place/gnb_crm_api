<?php

namespace App\Repositories;

use App\Models\ZohoCase;
use App\Repositories\shared\ZohoRepository;

class ZohoCaseRepository extends ZohoRepository
{
    public function __construct(ZohoCase $model)
    {
        parent::__construct($model);
    }

    public function create(array $attributes): ZohoCase
    {
        $case = array_merge($attributes, [
            "Status" => "Ubicado",
            "Caso_especial" => true,
            "Aseguradora" => auth()->user()->account_name,
            "Related_To" => auth()->user()->contact_name_id,
            "Subject" => "Asistencia remota",
            "Case_Origin" => "API",
        ]);
        return parent::create($case);
    }
}
