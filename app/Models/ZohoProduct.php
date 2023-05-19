<?php

namespace App\Models;

use App\Models\shared\ZohoModel;

class ZohoProduct extends ZohoModel
{
    protected string $modelName = 'Cases';

    protected array $fillable = [
        'id', 'Plataforma_API', 'Clave_API',
    ];
}