<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseRequest;
use App\Http\Resources\ZohoCaseResource;
use App\Models\ZohoCase;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    protected Repository $repository;

    public function __construct(ZohoCase $model)
    {
        $this->repository = new Repository($model);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cases = $this->repository->list($request->all());
        return ZohoCaseResource::collection($cases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CaseRequest $request)
    {
        $case = $this->repository->create($request->all());

        if (!$case)
            return response()->json(['code' => 404, 'message' => 'Case could not create.']);

        return new ZohoCaseResource($case);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $case = $this->repository->getById($id);

        if (!$case or !$case->can('belongToUser'))
            return response()->json(['message' => 'Case not found.']);

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
