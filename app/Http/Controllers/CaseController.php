<?php

namespace App\Http\Controllers;

use App\Facades\Zoho;
use Illuminate\Http\Request;
use App\Services\CaseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CaseResource;
use App\Http\Requests\Cases\StoreCaseRequest;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cases = Zoho::getRecords('Cases');

        return CaseResource::collection($cases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaseRequest $request)
    {
        $model = $this->service->create($request->validated());

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
