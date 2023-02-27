<?php

namespace App\Http\Controllers\Zoho;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zoho\CaseResource;
use App\Models\Zoho\ZohoCase;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cases = (new ZohoCase())->list($request->all(), true);
        return CaseResource::collection($cases[0])->additional($cases[1]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $case = (new ZohoCase())->create($request->all());

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
        $case = (new ZohoCase())->find($id);

        if (!$case)
            return response()->json([
                'code' => 404,
                'message' => 'Case not found.',
            ]);

        if ($case->isFinished())
            return response()->json([
                'code' => 204,
                'message' => 'Case finished.',
            ]);

        return new CaseResource($case);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
