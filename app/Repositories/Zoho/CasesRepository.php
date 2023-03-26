<?php

namespace App\Repositories\Zoho;

use App\Models\ZohoCase;

class CasesRepository extends ZohoRepository
{
    public function __construct(ZohoCase $model)
    {
        $this->model = $model;
    }
}
