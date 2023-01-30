<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "policy_number" => $this->P_liza,
            "chassis" => $this->Chasis,
            "vehicle_year" => $this->A_o,
            "vehicle_color" => $this->Color,
            "vehicle_make" => $this->Marca,
            "vehicle_model" => $this->Modelo,
            "vehicle_plate" => $this->Placa,
            "site_a" => $this->Punto_A,
            "site_b" => $this->Punto_B,
            "client_name" => $this->Asegurado,
            "phone" => $this->Phone,
            "policy_plan" => $this->Plan,
            "description" => $this->Description,
            "location_url" => $this->Ubicaci_n,
        ];
    }
}
