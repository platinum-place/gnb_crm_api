<?php

namespace App\Policies;

use App\Models\User;

class ZohoUserPolicy
{
    public function belongTo(User $user, object $record): bool
    {
        return $user->account_name_id === $record->Account_Name?->id;
    }
}
