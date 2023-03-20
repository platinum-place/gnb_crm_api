<?php

namespace App\Repositories\shared;

interface IApiRepository
{
    public function list(array $params);

    public function getById(string|int $id);
}
