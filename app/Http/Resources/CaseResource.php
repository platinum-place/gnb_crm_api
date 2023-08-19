<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'case_number' => $this->TUA,
            'created_date' => $this->Fecha,
            'company' => $this?->Account_Name?->name,
            'case_status' => $this->Status,
            'client' => [
                'name' => $this->Solicitante,
                'phone' => $this->Phone,
                'policy_plan' => $this->Plan,
                'policy_number' => $this->P_liza,
            ],
            'service' => [
                'name' => $this->Tipo_de_asistencia,
                'zone' => $this->Zona,
                'site_a' => $this->Punto_A,
                'site_b' => $this->Punto_B,
                'description' => $this->Description,
                'provider' => $this->Product_Name,
            ],
            'vehicle' => [
                'chassis' => $this->Chasis,
                'year' => $this->A_o,
                'color' => $this->Color,
                'make' => $this->Marca,
                'model' => $this->Modelo,
                'plate' => $this->Placa,
            ],
            'assisted_by' => $this?->Related_To?->name,
            'times' => [
                'start' => $this->Llamada,
                'sent_service' => $this->Despacho,
                'service_contacted' => $this->Contacto,
                'end' => $this->Cierre,
            ],
        ];
    }
}
