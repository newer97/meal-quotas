<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        Log::info('User: ' . $user->id . ' is trying to view students. role: ' . $user->roles->pluck('name')->implode(', '));
        return $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
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
    public function update(User $user, Student $student): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        Log::info('User: ' . $user->id . ' is trying to delete student: ' . $student->id . "role: " . $user->roles->pluck('name')->implode(', '));
        return $user->hasRole(['superadmin']);
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole(['superadmin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
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
    public function forceDelete(User $user, Student $student): bool
    {
        return $user->hasRole(['superadmin']);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole(['superadmin']);
    }
}
