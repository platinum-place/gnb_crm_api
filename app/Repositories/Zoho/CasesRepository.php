<?php

namespace App\Repositories\Zoho;

use App\Models\Cases;

class CasesRepository extends ZohoRepository
{
    public function __construct(Cases $model)
    {
        return parent::__construct($model);
    }
}
