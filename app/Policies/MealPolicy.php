<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MealPolicy
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
    public function view(User $user, Meal $meal): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Meal $meal): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Meal $meal): bool
    {
        return $user->hasRole(['superadmin']);
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole(['superadmin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Meal $meal): bool
    {
        return $user->hasRole(['superadmin']);
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasRole(['superadmin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Meal $meal): bool
    {
        return $user->hasRole(['superadmin']);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole(['superadmin']);
    }
}
