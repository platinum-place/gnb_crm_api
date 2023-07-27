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
            'id' => $this->getId(),
            'case_number' => $this->getKeyValue('TUA'),
            'created_date' => $this->getKeyValue('Fecha'),
            'company' => $this->getKeyValue('Account_Name')->getKeyValue('name'),
            'case_status' => $this->getKeyValue('Status')->getValue(),
            'client' => [
                'name' => $this->getKeyValue('Solicitante'),
                'phone' => $this->getKeyValue('Phone'),
                'policy_plan' => $this->getKeyValue('Plan')?->getValue(),
                'policy_number' => $this->getKeyValue('P_liza'),
            ],
            'service' => [
                'name' => $this->getKeyValue('Tipo_de_asistencia')?->getValue(),
                'zone' => $this->getKeyValue('Zona')?->getValue(),
                'site_a' => $this->getKeyValue('Punto_A'),
                'site_b' => $this->getKeyValue('Punto_B'),
                'description' => $this->getKeyValue('Description'),
                'provider' => $this->getKeyValue('Product_Name')?->getKeyValue('name'),
            ],
            'vehicle' => [
                'chassis' => $this->getKeyValue('Chasis'),
                'year' => $this->getKeyValue('A_o'),
                'color' => $this->getKeyValue('Color'),
                'make' => $this->getKeyValue('Marca'),
                'model' => $this->getKeyValue('Modelo'),
                'plate' => $this->getKeyValue('Placa'),
            ],
            'assisted_by' => $this->getKeyValue('Related_To')?->getKeyValue('name'),
            'times' => [
                'start' => $this->getKeyValue('Llamada'),
                'sent_service' => $this->getKeyValue('Despacho'),
                'service_contacted' => $this->getKeyValue('Contacto'),
                'end' => $this->getKeyValue('Cierre'),
            ],
        ];
    }
}
