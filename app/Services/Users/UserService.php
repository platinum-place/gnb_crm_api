<?php

namespace App\Services\Users;

use App\Models\Users\User;
use App\Services\shared\BaseService;

class UserService extends BaseService
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
