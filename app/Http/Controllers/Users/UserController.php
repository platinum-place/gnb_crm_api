<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use App\Http\Responses\HttpResponse;
use App\Services\Users\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $models = $this->service->list($request->all());

        return UserResource::collection($models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $model = $this->service->create($request->all());

        return new UserResource($model);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = $this->service->getBy($id);

        return new UserResource($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $model = $this->service->update($id, $request->all());

        return new UserResource($model);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);

        return HttpResponse::delete();
    }
}
