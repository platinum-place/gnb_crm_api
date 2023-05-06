<?php

namespace App\Policies;

use App\Models\User;

class ZohoUserPolicy
{
    /**
     * Determine if the given array can be belong to user.
     */
    public function belongTo(User $user, array $record): bool
    {
        return $user->account_name_id === $record['Account_Name']['id'];
    }
}
