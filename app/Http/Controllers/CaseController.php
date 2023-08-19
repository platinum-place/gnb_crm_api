<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CaseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CaseResource;
use App\Http\Requests\Cases\CaseRequest;
use App\Http\Requests\Cases\StoreCaseRequest;

class CaseController extends Controller
{
    protected CaseService $service;

    public function __construct(CaseService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CaseRequest $request)
    {
        $models = $this->service->filter($request->all());

        return CaseResource::collection($models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaseRequest $request)
    {
        $attr = array_merge($request->validated(), [
            'Status' => 'Ubicado',
            'Caso_especial' => true,
            'Aseguradora' => auth()->user()->account_name,
            'Related_To' => auth()->user()->contact_name_id,
            'Subject' => 'Asistencia remota',
            'Case_Origin' => 'API',
        ]);

        $model = $this->service->create($attr);

        return new CaseResource($model);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = $this->service->findById($id);

        if (auth()->user()->account_name_id != $model?->Account_Name?->id) {
            throw new \Exception(__('Unauthorized action.'), 403);
        }

        return (new CaseResource($model))->additional(['location' => $this->service->getLocation($model)]);
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
