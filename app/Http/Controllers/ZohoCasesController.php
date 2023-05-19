<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZohoCaseCreateRequest;
use App\Http\Requests\ZohoCaseRequest;
use App\Http\Resources\ZohoCaseCollectionResource;
use App\Http\Resources\ZohoCaseResource;
use App\Repositories\ZohoCaseRepository;
use Illuminate\Http\Request;

class ZohoCasesController extends Controller
{
    protected ZohoCaseRepository $repository;

    public function __construct(ZohoCaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ZohoCaseRequest $request)
    {
        $cases = $this->repository->list($request->all());
        return ZohoCaseCollectionResource::collection($cases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ZohoCaseCreateRequest $request)
    {
        $case = $this->repository->create($request->all());
        return new ZohoCaseResource($case);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $case = $this->repository->getById($id);

        if ($request->user()->cannot('belongTo', $case))
            abort(403, 'Unauthorized action.'); //TODO: usar clases de excepcion

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
