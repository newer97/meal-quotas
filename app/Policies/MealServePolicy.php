<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MealServe;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealServePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_meal::serve');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MealServe $mealServe): bool
    {
        return $user->can('view_meal::serve');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_meal::serve');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MealServe $mealServe): bool
    {
        return $user->can('update_meal::serve');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MealServe $mealServe): bool
    {
        return $user->can('delete_meal::serve');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_meal::serve');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, MealServe $mealServe): bool
    {
        return $user->can('force_delete_meal::serve');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_meal::serve');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, MealServe $mealServe): bool
    {
        return $user->can('restore_meal::serve');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_meal::serve');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, MealServe $mealServe): bool
    {
        return $user->can('replicate_meal::serve');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_meal::serve');
    }
}
