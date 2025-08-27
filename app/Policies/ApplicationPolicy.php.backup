<?php

namespace App\Policies;

use App\Models\User;

class ApplicationPolicy
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
    public function view(User $user, $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can prequalify applications.
     */
    public function prequalify(User $user, $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can schedule interviews.
     */
    public function interview(User $user, $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can approve applications.
     */
    public function approve(User $user, $application): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can reject applications.
     */
    public function reject(User $user, $application): bool
    {
        return $user->hasRole('admin');
    }
}
