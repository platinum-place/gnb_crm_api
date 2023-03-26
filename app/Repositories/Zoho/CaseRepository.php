<?php

namespace App\Repositories\Zoho;

use App\Models\ZohoCase;

class CaseRepository extends ZohoRepository
{
    public function __construct(ZohoCase $model)
    {
        $this->model = $model;
    }
}
