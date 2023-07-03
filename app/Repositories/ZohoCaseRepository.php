<?php

namespace App\Repositories;

use App\Repositories\shared\ZohoRepository;

class ZohoCaseRepository extends ZohoRepository
{
    public function __construct()
    {
        parent::__construct('cases');
    }

    public function create(array $params = []): array
    {
        $params = array_merge($params, [
            'Status' => 'Ubicado',
            'Caso_especial' => true,
            'Aseguradora' => auth()->user()->account_name,
            'Related_To' => auth()->user()->contact_name_id,
            'Subject' => 'Asistencia remota',
            'Case_Origin' => 'API',
        ]);

        return parent::create($params);
    }
}
