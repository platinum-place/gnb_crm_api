<?php

namespace App\Policies;

use App\Models\User;

class ZohoUserPolicy
{
    public function notBelongTo(User $user, array $record): bool
    {
        return $user->account_name_id !== $record['Account_Name']['id'];
    }
}
