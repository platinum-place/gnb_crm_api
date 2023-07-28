<?php

namespace App\Services;

class CaseService
{
    function replaceRequest(array $request)
    {
        $data = [
            'P_liza' => 'policy_number',
            'Chasis' => 'chassis',
            'A_o' => 'vehicle_year',
            'Color' => 'vehicle_color',
            'Marca' => 'vehicle_make',
            'Modelo' => 'vehicle_model',
            'Placa' => 'vehicle_plate',
            'Punto_A' => 'site_a',
            'Punto_B' => 'site_b',
            'Solicitante' => 'client_name',
            'Phone' => 'phone',
            'Plan' => 'policy_plan',
            'Description' => 'description',
            'Ubicaci_n' => 'location_url',
            'Tipo_de_asistencia' => 'service',
            'Zona' => 'zone',
            'Asegurado' => 'client_name',
        ];

        $data = [];

        foreach ($data as $newIndex => $oldIndex) {
            if (isset($request[$oldIndex])) {
                $data[$newIndex] = $request[$oldIndex];
            }
        }

        return $data;
    }

    function includeParams(array $data)
    {
        return array_merge($data, [
            'Status' => 'Ubicado',
            'Caso_especial' => true,
            'Aseguradora' => auth()->user()->account_name,
            'Related_To' => auth()->user()->contact_name_id,
            'Subject' => 'Asistencia remota',
            'Case_Origin' => 'API',
        ]);
    }

    function includeZohoParams(array $data)
    {
        $data['Plan'] = [$data['Plan'], 'com\zoho\crm\api\util\Choice'];
        $data['Tipo_de_asistencia'] = [$data['Tipo_de_asistencia'], 'com\zoho\crm\api\util\Choice'];
        $data['Case_Origin'] = [$data['Case_Origin'], 'com\zoho\crm\api\util\Choice'];
        $data['Zona'] = [$data['Zona'], 'com\zoho\crm\api\util\Choice'];
        $data['Status'] = [$data['Status'], 'com\zoho\crm\api\util\Choice'];
        $data['Related_To'] = [$data['Related_To'], 'com\zoho\crm\api\record\Record'];
        return $data;
    }
}
