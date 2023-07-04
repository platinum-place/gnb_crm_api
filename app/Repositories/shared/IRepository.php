<?php

namespace App\Repositories\shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IRepository
{
    public function list(array $params = []): LengthAwarePaginator;
    public function create(array $params): Model;
    public function find(string $id): Model;
    public function update(string $id, array $params): Model;
    public function delete(string $id): void;
}
