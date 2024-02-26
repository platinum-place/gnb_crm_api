<?php

namespace App\Models;

class ZohoCase
{
    public static array $map = [
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

    public static function FunctionName()
    {
        return array_keys(self::$map);
    }
}
