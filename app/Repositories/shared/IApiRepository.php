<?php

namespace App\Repositories\shared;

interface IApiRepository
{
    public function list(array $params);

    public function getById(int|string $id);

    public function create(array $data);
}
