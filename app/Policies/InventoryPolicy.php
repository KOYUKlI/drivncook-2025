<?php

namespace App\Policies;

use App\Models\User;

class InventoryPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool { return false; }
    public function adjust(User $user): bool { return false; }
    public function move(User $user): bool { return false; }
}
