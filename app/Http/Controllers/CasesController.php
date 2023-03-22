<?php

namespace App\Http\Controllers;

use App\Http\Resources\CasesResource;
use App\Repositories\Zoho\CasesRepository;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    protected CasesRepository $repository;

    public function __construct(CasesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cases = $this->repository->list($request->all());
        return CasesResource::collection($cases[0])->additional($cases[1]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

        if ($this->repository->isFinished($case))
            return response()->json(['message' => 'Case finished.']);

        return new CasesResource($case);
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
