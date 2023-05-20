<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ZohoCase;
use App\Models\User;

class ZohoCasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ZohoCase $zohoCase): bool
    {
        return $user->account_name_id === $zohoCase->Account_Name?->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ZohoCase $zohoCase): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ZohoCase $zohoCase): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ZohoCase $zohoCase): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ZohoCase $zohoCase): bool
    {
        return true;
    }
}
