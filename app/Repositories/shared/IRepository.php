<?php

namespace App\Repositories\shared;

use Illuminate\Pagination\LengthAwarePaginator;

interface IRepository
{
    public function list(array $params = []): LengthAwarePaginator;
}
