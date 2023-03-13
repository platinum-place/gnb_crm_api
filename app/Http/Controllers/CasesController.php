<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Repositories\ZohoRepository;
use Illuminate\Http\Request;
use PlatinumPlace\LaravelZohoCrmApi\Facades\Zoho;

class CasesController extends Controller
{
    protected ZohoRepository $repository;

    public function __construct(Cases $case)
    {
        $this->repository = new ZohoRepository($case);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cases = $this->repository->list($request->all());
        return Zoho::getRecords("Cases", ["per_page" => 10]);
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
