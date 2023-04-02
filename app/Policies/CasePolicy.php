<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ZohoCase;

class CasePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Perform pre-authorization checks.
     */
    public function belongToUser(User $user, ZohoCase $case): bool|null
    {
        if ($case->Account_Name["id"] == $user->account_name_id) {
            return true;
        }
        return null;
    }
}
