<?php

namespace App\Policies;

use App\Models\User;

class ReportPdfPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse', 'franchisee']);
    }

    public function view(User $user, $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    public function delete(User $user, $model): bool
    {
        return $user->hasRole('admin');
    }
}
