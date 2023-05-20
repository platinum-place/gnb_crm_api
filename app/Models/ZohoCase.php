<?php

namespace App\Models;

use App\Models\shared\ZohoModel;
use App\Facades\Navixy;
use App\Facades\Systrack;

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

    public function getStatus()
    {
        return in_array(
            $this->Status,
            [
                'Medio servicio',
                'Cancelado',
                'Contacto',
                'Cerrado',
            ]
        ) ? 'Finalizado' : 'En progreso';
    }

    public function getLocation()
    {
        if (!$this->Product_Name)
            return;

        $service = (new ZohoProduct())->newQuery()->find($this->Product_Name->id);

        if ($service->Plataforma_API)
            return match ($service->Plataforma_API) {
                'Systrack' => Systrack::getLocation($service->Clave_API),
                'Navixy' => Navixy::getLocation($service->Clave_API),
            };
    }
}
