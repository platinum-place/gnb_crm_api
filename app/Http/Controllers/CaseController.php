<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreZohoCaseRequest;
use App\Http\Resources\ZohoCaseCollectionResource;
use App\Http\Resources\ZohoCaseResource;
use App\Repositories\ZohoCaseRepository;
use Exception;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    protected ZohoCaseRepository $repository;

    public function __construct(ZohoCaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cases = $this->repository->list($request->all());

        return ZohoCaseCollectionResource::collection($cases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreZohoCaseRequest $request)
    {
        $case = $this->repository->create($request->all());

        return new ZohoCaseResource($case);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $case = $this->repository->find($id);

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
