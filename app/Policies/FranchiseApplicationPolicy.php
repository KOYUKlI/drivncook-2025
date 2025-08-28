<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FranchiseApplication;

class FranchiseApplicationPolicy
{
    /**
     * Determine whether the user can view any applications.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the application.
     */
    public function view(User $user, FranchiseApplication $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the application.
     */
    public function update(User $user, FranchiseApplication $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can prequalify applications.
     */
    public function prequalify(User $user, FranchiseApplication $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can schedule interviews.
     */
    public function interview(User $user, FranchiseApplication $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can approve applications.
     */
    public function approve(User $user, FranchiseApplication $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can reject applications.
     */
    public function reject(User $user, FranchiseApplication $application): bool
    {
        return $user->hasRole('admin');
    }
}
