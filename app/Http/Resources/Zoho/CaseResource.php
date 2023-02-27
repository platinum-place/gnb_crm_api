<?php

namespace App\Http\Resources\Zoho;

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
            "id" => $this->TUA,
            "created_date" => $this->Fecha,
            "case_status" => $this->Status,
            "client" => [
                "name" => $this->Solicitante,
                "phone" => $this->Phone,
                "policy_plan" => $this->Plan,
                "policy_number" => $this->P_liza,
            ],
            "service_info" => [
                "zone" => $this->Zona,
                "site_a" => $this->Punto_A,
                "site_b" => $this->Punto_B,
                "description" => $this->Description,
                "service" => $this->Tipo_de_asistencia,
            ],
            "vehicle" => [
                "chassis" => $this->Chasis,
                "year" => $this->A_o,
                "color" => $this->Color,
                "make" => $this->Marca,
                "model" => $this->Modelo,
                "plate" => $this->Placa,
            ],
            "assisted_by" => $this->Related_To["name"],
            "gps_provider" => [
                "lat" => $this->provider->location->lat ?? null,
                "lng" => $this->provider->location->lng ?? null,
            ],
            "times" => [
                "start" => $this->Llamada,
                "sent_service" => $this->Despacho,
                "service_contacted" => $this->Contacto,
                "end" => $this->Cierre,
            ],
        ];
    }
}
