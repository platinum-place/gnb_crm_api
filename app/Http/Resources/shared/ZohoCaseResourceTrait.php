<?php

namespace App\Http\Resources\shared;

trait ZohoCaseResourceTrait
{
    public function case ()
    {
        return [
            'id' => $this->id,
            'case' => [
                'company' => $this->Account_Name?->name,
                'case_number' => $this->TUA,
                'created_date' => $this->Fecha,
                'case_status' => $this->getStatus(),
            ],
            'client' => [
                'name' => $this->Solicitante,
                'phone' => $this->Phone,
                'policy_plan' => $this->Plan,
                'policy_number' => $this->P_liza,
            ],
            'service_info' => [
                'zone' => $this->Zona,
                'site_a' => $this->Punto_A,
                'site_b' => $this->Punto_B,
                'description' => $this->Description,
                'service' => $this->Tipo_de_asistencia,
                'provider' => $this->Product_Name?->name,
            ],
            'vehicle' => [
                'chassis' => $this->Chasis,
                'year' => $this->A_o,
                'color' => $this->Color,
                'make' => $this->Marca,
                'model' => $this->Modelo,
                'plate' => $this->Placa,
            ],
            'assisted_by' => $this->Related_To?->name,
            'times' => [
                'start' => $this->Llamada,
                'sent_service' => $this->Despacho,
                'service_contacted' => $this->Contacto,
                'end' => $this->Cierre,
            ],
        ];
    }
}
