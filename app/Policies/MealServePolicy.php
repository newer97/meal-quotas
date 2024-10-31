<?php

namespace App\Policies;

use App\Models\MealServe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MealServePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MealServe $mealServe): bool
    {
        return $user->hasRole(['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'superadmin', 'cashier']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MealServe $mealServe): bool
    {
        return $user->hasRole(['superadmin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MealServe $mealServe): bool
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
    public function restore(User $user, MealServe $mealServe): bool
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
    public function forceDelete(User $user, MealServe $mealServe): bool
    {
        return $user->hasRole(['superadmin']);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole(['superadmin']);
    }
}
