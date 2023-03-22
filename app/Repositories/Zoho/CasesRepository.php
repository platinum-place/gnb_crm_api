<?php

namespace App\Repositories\Zoho;

use App\Facades\Navixy;
use App\Facades\Systrack;
use App\Facades\Zoho;
use Illuminate\Support\Collection;

class CasesRepository extends ZohoRepository
{
    protected string $module = 'Cases';

    public function setGSProvidersCollection(Collection $collection)
    {
        $sefl = $this;
        return $collection->map(function ($case) use ($sefl) {
            return $sefl->setGSProviderModel($case);
        });
    }

    public function setGSProviderModel($case)
    {
        return $case->fill(["gpsProvider" => ($case->Product_Name) ? $this->getGPS($case->Product_Name["id"]) : null]);
    }

    private function getGPS(int $product_id)
    {
        $product = Zoho::setModule("Products")->find($product_id);

        if ($product->Plataforma_API)
            return match ($product->Plataforma_API) {
                'Systrack' => Systrack::getLocation($product->Clave_API),
                'Navixy' => Navixy::getLocation($product->Clave_API),
            };

        return [];
    }

    public function isFinished($model): bool
    {
        return in_array(
            $model->Status,
            [
                "Medio servicio",
                "Cancelado",
                "Contacto",
                "Cerrado",
            ]
        );
    }

    public function getById(string|int $id)
    {
        $case = parent::getById($id);

        if ($case)
            return $this->setGSProviderModel($case);
    }

    public function list(array $params)
    {
        $cases = parent::list($params);
        return [
            $this->setGSProvidersCollection($cases->get("data")),
            $cases->get("meta")
        ];
    }
}
