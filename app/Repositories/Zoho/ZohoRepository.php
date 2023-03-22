<?php

namespace App\Repositories\Zoho;

use App\Facades\Zoho;
use App\Repositories\shared\IApiRepository;
use Illuminate\Support\Facades\Auth;

abstract class ZohoRepository implements IApiRepository
{
    protected string $module = '';

    public function list(array $params)
    {
        $list = Zoho::setModule($this->module);

        foreach ($params as $key => $value) {
            $list->where($key, $value);
        }

        return $list->get();
    }

    public function getById(string|int $id)
    {
        $model = Zoho::setModule($this->module)->find($id);

        if (!$this->belongToUser($model, Auth::user()->account_name_id))
            return null;

        return $model;
    }

    public function belongToUser($model, $id)
    {
        return $model->Account_Name["id"] == $id;
    }
}
