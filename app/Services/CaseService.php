<?php

namespace App\Services;

use App\Facades\Navixy;
use App\Facades\Systrack;
use App\Facades\Zoho;
use App\Models\ZohoCase;
use App\Models\shared\ApiModel;

class CaseService
{
    protected array $map = [
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

    private function mapParams(array &$params = [])
    {
        foreach ($this->map as $key => $value) {
            if (isset($params[$value]) && !is_null($value)) {
                $params[$key] = $params[$value];
                unset($params[$value]);
            }
        }
    }

    function filter(array $params = [])
    {
        $this->mapParams($params);

        return Zoho::getRecords('Cases', $params);
    }

    function create(array $params)
    {
        $this->mapParams($params);

        $attr = array_merge($params, [
            'Status' => 'Ubicado',
            'Caso_especial' => true,
            'Aseguradora' => auth()->user()->account_name,
            'Related_To' => auth()->user()->contact_name_id,
            'Subject' => 'Asistencia remota',
            'Case_Origin' => 'API',
        ]);

        return Zoho::create('Cases', $attr);
    }

    function findById(int|string $id)
    {
        return Zoho::getRecord('Cases', $id);
    }

    public function getLocation(ApiModel $case)
    {
        if ($id = $case?->Product_Name?->id) {
            $service = Zoho::getRecord('Products', $id);

            if ($api = $service?->Plataforma_API) {
                return match ($api) {
                    'Systrack' => Systrack::list($service->Clave_API),
                    'Navixy' =>Navixy::find($service->Clave_API),
                };
            }
        }
    }
}
