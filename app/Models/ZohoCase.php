<?php

namespace App\Models;

use App\Models\shared\ZohoModel;

class ZohoCase extends ZohoModel
{
    protected string $modelName = 'Cases';

    protected array $fillable = [
        'id', 'TUA', 'Account_Name',
        'Solicitante', 'Phone', 'Plan',
        'P_liza', 'Zona', 'Punto_A',
        'Punto_B', 'Description', 'Tipo_de_asistencia',
        'Product_Name', 'Chasis', 'A_o',
        'Color', 'Marca', 'Modelo',
        'Placa', 'Related_To', 'Llamada',
        'Despacho', 'Contacto', 'Cierre',
        'Status', 'Product_Name', 'Fecha',
    ];
}