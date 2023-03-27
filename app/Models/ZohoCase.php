<?php

namespace App\Models;

class ZohoCase extends ZohoModel
{
    public string $module = "Cases";

    protected array $mutable = [
        "P_liza" => "policy_number",
        "Chasis" => "chassis",
        "A_o" => "vehicle_year",
        "Color" => "vehicle_color",
        "Marca" => "vehicle_make",
        "Modelo" => "vehicle_model",
        "Placa" => "vehicle_plate",
        "Punto_A" => "site_a",
        "Punto_B" => "site_b",
        "Solicitante" => "client_name",
        "Phone" => "phone",
        "Plan" => "policy_plan",
        "Description" => "description",
        "Ubicaci_n" => "location_url",
        "Tipo_de_asistencia" => "service",
        "Zona" => "zone",
        "Asegurado" => "client_name",
    ];

    public function provider()
    {
        if ($this->Product_Name)
            return [
                "provider" => $this->Product_Name["name"],
                "locations" => (new ZohoProduct)->find($this->Product_Name["id"])->location
            ];
    }

    public function isFinished(): bool
    {
        if ($this->Status)
            return in_array(
                $this->Status,
                [
                    "Medio servicio",
                    "Cancelado",
                    "Contacto",
                    "Cerrado",
                ]
            );
    }

    public function create(array $data)
    {
        return parent::create(array_merge($data, [
            "Status" => "Ubicado",
            "Caso_especial" => true,
            "Aseguradora" => auth()->user()->account_name,
            "Related_To" => auth()->user()->contact_name_id,
            "Subject" => "Asistencia remota",
            "Case_Origin" => "API",
        ]));
    }
}
