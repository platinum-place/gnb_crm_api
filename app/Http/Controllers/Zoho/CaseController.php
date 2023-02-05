<?php

namespace App\Http\Controllers\Zoho;

use App\Http\Controllers\Controller;
use App\Http\Requests\Zoho\CaseRequest;
use App\Http\Resources\Zoho\CaseResource;
use App\Models\Zoho\CaseZoho;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    protected string $zohoModuleName = 'Cases';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cases = (new CaseZoho())->list($request->all());
        return CaseResource::collection($cases[0])->additional($cases[1]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CaseRequest $request)
    {
        $case = (new CaseZoho())->create($request->all());

        if (!$case)
            return response()->json([
                'code' => 404,
                'message' => 'Case could not create.',
            ]);

        return new CaseResource($case);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $case = (new CaseZoho())->find($id);

        if (!$case)
            return response()->json([
                'code' => 404,
                'message' => 'Case not found.',
            ]);

        if ($case->isfinished())
            return response()->json([
                'code' => 204,
                'message' => 'Case finished.',
            ]);

        return new CaseResource($case);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
