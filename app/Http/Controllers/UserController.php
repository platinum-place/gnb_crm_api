<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\HttpResponse;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $models = $this->repository->list($request->all());

        return UserResource::collection($models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $model = $this->repository->create($request->all());

        return new UserResource($model);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = $this->repository->find($id);

        return new UserResource($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $model = $this->repository->update($id, $request->all());

        return new UserResource($model);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->repository->delete($id);

        return HttpResponse::delete();
    }
}
