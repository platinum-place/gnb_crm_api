<?php

namespace App\Services;

use App\Models\shared\ApiModel;
use App\Models\ZohoCase;
use App\Services\shared\ZohoApi;
use App\Services\shared\ApiService;
use App\Services\shared\NavixyApi;
use App\Services\shared\SystrackApi;

class CaseService
{
    use ZohoApi, SystrackApi, NavixyApi;

    public string $module = 'Cases';

    public function getLocation(ApiModel $case): array
    {
        $location = [];

        if ($id = $case?->Product_Name?->id) {
            $this->module = 'Products';
            $service = $this->findById($id);

            if ($api = $service?->Plataforma_API) {
                $location = match ($api) {
                    'Systrack' => $this->findBySystrackId($service->Clave_API),
                    'Navixy' => $this->findByNavixyId($service->Clave_API),
                };
            }
        }

        return $location->trackPoint->position;
    }
}
