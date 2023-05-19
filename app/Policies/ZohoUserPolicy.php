<?php

namespace App\Policies;

use App\Models\User;
use App\Models\shared\ZohoModel;

class ZohoUserPolicy
{
    public function belongTo(User $user, ZohoModel $record): bool
    {
        return $user->account_name_id === $record->Account_Name?->id;
    }
}
