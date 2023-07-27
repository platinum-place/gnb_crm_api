<?php

namespace App\Http\Controllers;

use App\Facades\Navixy;
use App\Facades\Systrack;
use App\Facades\Zoho;
use App\Http\Requests\StoreCaseRequest;
use App\Http\Resources\CaseResource;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $records = Zoho::searchRecords('Cases', $request->all());

        return CaseResource::collection($records);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaseRequest $request)
    {
        $data = array_merge($request->all(), [
            'Status' => 'Ubicado',
            'Caso_especial' => true,
            'Aseguradora' => auth()->user()->account_name,
            'Related_To' => auth()->user()->contact_name_id,
            'Subject' => 'Asistencia remota',
            'Case_Origin' => 'API',
        ]);

        $record = Zoho::createRecords('Cases', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Zoho::getRecord('Cases', $id);

        if (auth()->user()->account_name_id != $record->getKeyValue('Account_Name')->getKeyValue('id')) {
            throw new \Exception(__('Unauthorized action.'), 403);
        }

        $location = [];

        if ($id = $record->getKeyValue('Product_Name')?->getKeyValue('id')) {
            $service = Zoho::getRecord('Products', $id);

            if ($api = $service->getKeyValue('Plataforma_API')?->getValue()) {
                $location = match ($api) {
                    'Systrack' => Systrack::getLocation($service->getKeyValue('Clave_API')),
                    'Navixy' => Navixy::getLocation($service->getKeyValue('Clave_API')),
                };
            }
        }

        return (new CaseResource($record))->additional(['location' => $location]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
