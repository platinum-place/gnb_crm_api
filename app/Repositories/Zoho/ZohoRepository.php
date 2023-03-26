<?php

namespace App\Repositories\Zoho;

use App\Facades\Zoho;
use App\Models\ZohoModel;
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
        $query = $this->model;
        foreach ($params as $key => $value) {
            $query = $query->where($key, $value);
        }
        return $query->get();
    }

    public function getById(string|int $id)
    {
        $model = $this->model->find($id);

        if (!$this->belongToUser($model, Auth::user()->account_name_id))
            return null;

        return $model;
    }

    public function belongToUser($model, $id)
    {
        return $model->Account_Name["id"] == $id;
    }
}
