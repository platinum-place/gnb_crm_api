<?php

namespace App\Http\Controllers;

use App\Facades\Zoho;
use App\Http\Requests\StoreZohoCaseRequest;
use App\Http\Requests\ZohoCaseRequest;
use App\Http\Resources\ZohoCaseCollectionResource;
use App\Http\Resources\ZohoCaseResource;
use Exception;
use Illuminate\Http\Request;

class ZohoCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ZohoCaseRequest $request)
    {
        $cases = Zoho::getRecords('cases', $request->all());

        return ZohoCaseCollectionResource::collection($cases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreZohoCaseRequest $request)
    {
        $request->merge([
            'Status' => 'Ubicado',
            'Caso_especial' => true,
            'Aseguradora' => auth()->user()->account_name,
            'Related_To' => auth()->user()->contact_name_id,
            'Subject' => 'Asistencia remota',
            'Case_Origin' => 'API',
        ]);

        $id = Zoho::getRecords('cases', $request->all());

        return $this->show($id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $case = Zoho::getRecord('cases', $id);

        if (auth()->user()->account_name_id != $case['Account_Name']['id']) {
            throw new Exception('Unauthorized action.', 403);
        }

        return new ZohoCaseResource($case);
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
