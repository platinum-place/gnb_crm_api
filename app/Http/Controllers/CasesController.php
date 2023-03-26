<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseRequest;
use App\Http\Resources\CaseResource;
use App\Repositories\Zoho\CaseRepository;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    protected CaseRepository $repository;

    public function __construct(CaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cases = $this->repository->list($request->all());
        return CaseResource::collection($cases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CaseRequest $request)
    {
        $case = $this->repository->create($request->all());

        if (!$case)
            return response()->json([
                'code' => 404,
                'message' => 'Case could not create.',
            ]);

        return new CaseResource($case);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        /** @var \App\Models\Cases */
        $case = $this->repository->getById($id);

        if (!$case)
            return response()->json(['message' => 'Case not found.']);

        if ($case->isFinished())
            return response()->json(['message' => 'Case finished.']);

        return new CaseResource($case);
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
