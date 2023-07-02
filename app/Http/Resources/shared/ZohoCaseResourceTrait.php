<?php

namespace App\Http\Resources\shared;

use App\Facades\Navixy;
use App\Facades\Systrack;
use App\Facades\Zoho;

trait ZohoCaseResourceTrait
{
    public function getStatus()
    {
        return in_array(
            $this['Status'],
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
        if (empty($this['Product_Name'])) {
            return;
        }

        $service = Zoho::getRecord('Products', $this['Product_Name']['id']);

        if (! empty($service['Plataforma_API'])) {
            return match ($service['Plataforma_API']) {
                'Systrack' => Systrack::getLocation($service['Clave_API']),
                'Navixy' => Navixy::getLocation($service['Clave_API']),
            };
        }
    }

    public function case()
    {
        return [
            'id' => $this['id'],
            'case' => [
                'company' => $this['Account_Name']['name'] ?? '',
                'case_number' => $this['TUA'],
                'created_date' => $this['Fecha'],
                'case_status' => $this->getStatus(),
            ],
            'client' => [
                'name' => $this['Solicitante'],
                'phone' => $this['Phone'],
                'policy_plan' => $this['Plan'],
                'policy_number' => $this['P_liza'],
            ],
            'service' => [
                'name' => $this['Tipo_de_asistencia'],
                'zone' => $this['Zona'],
                'site_a' => $this['Punto_A'],
                'site_b' => $this['Punto_B'],
                'description' => $this['Description'],
            ],
            'provider' => [
                'name' => $this['Product_Name']['name'] ?? '',
            ],
            'vehicle' => [
                'chassis' => $this['Chasis'],
                'year' => $this['A_o'],
                'color' => $this['Color'],
                'make' => $this['Marca'],
                'model' => $this['Modelo'],
                'plate' => $this['Placa'],
            ],
            'assisted_by' => $this['Related_To']['name'] ?? '',
            'times' => [
                'start' => $this['Llamada'],
                'sent_service' => $this['Despacho'],
                'service_contacted' => $this['Contacto'],
                'end' => $this['Cierre'],
            ],
        ];
    }
}
