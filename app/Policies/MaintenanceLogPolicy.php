<?php

namespace App\Policies;

use App\Models\MaintenanceLog;
use App\Models\User;

class MaintenanceLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'fleet']);
    }

    public function view(User $user, MaintenanceLog $log): bool
    {
        return $user->hasRole(['admin', 'fleet']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'fleet']);
    }

    public function update(User $user, MaintenanceLog $log): bool
    {
        return $user->hasRole(['admin', 'fleet']);
    }

    public function close(User $user, MaintenanceLog $log): bool
    {
        return $user->hasRole(['admin', 'fleet']);
    }
}
