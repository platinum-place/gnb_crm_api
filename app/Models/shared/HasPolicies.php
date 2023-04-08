<?php

namespace App\Models\shared;

use Illuminate\Support\Facades\Gate;

trait HasPolicies
{
    public function can(string $policy)
    {
        return Gate::allows($policy, $this);
    }
}
