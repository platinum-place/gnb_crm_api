<?php

namespace App\Models;

use App\Models\shared\ZohoModel;

class CaseZoho extends ZohoModel
{
    protected string $moduleName = 'Cases';

    protected array $fillable = [
        "P_liza",
        "Chasis"
    ];

    protected array $mutate = [
        // "sss" => "vehicle_year",
        "Chasis" => "chassis",
        // "sss" => "vehicle_color",
        // "sss" => "plate",
        // "sss" => "vehicle_make",
        // "sss" => "vehicle_model",
        // "sss" => "site_a",
        // "sss" => "site_b",
        "P_liza" => "policy_number",
        // "sss" => "client_name",
        // "sss" => "phone",
        // "sss" => "policy_plan",
        // "sss" => "description",
        // "sss" => "location_url",
    ];
}
