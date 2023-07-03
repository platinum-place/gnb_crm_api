<?php

namespace App\Repositories\shared;

use Illuminate\Pagination\LengthAwarePaginator;

interface IApiRepository
{
    public function list(array $params = []): LengthAwarePaginator;

    public function create(array $params): array;

    public function find(string $id): array;
}
