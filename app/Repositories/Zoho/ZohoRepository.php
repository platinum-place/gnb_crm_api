<?php

namespace App\Repositories\Zoho;

use App\Models\shared\ZohoModel;
use App\Repositories\shared\IApiRepository;
use Illuminate\Support\Facades\Auth;

abstract class ZohoRepository implements IApiRepository
{
    protected ZohoModel $model;

    public function __construct(ZohoModel $model)
    {
        $this->model = $model;
    }

    public function list(array $params)
    {
        $list = $this->model->newBuilder();
        foreach ($params as $key => $value) {
            $list->where($key, $value);
        }
        return $list->get();
    }

    public function getById(string|int $id)
    {
        $model = $this->model->newBuilder()->find($id);

        if (!$model->belongToUser(Auth::user()->account_name_id))
            return null;

        return $model;
    }
}
