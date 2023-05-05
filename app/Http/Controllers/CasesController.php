<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseRequest;
use App\Http\Requests\ZohoCaseRequest;
use App\Http\Resources\ZohoCaseResource;
use App\Repositories\ZohoRepository;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    protected ZohoRepository $repository;

    public function __construct()
    {
        $this->repository = new ZohoRepository('Cases');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ZohoCaseRequest $request)
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
