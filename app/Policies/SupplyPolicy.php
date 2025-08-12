<?php

namespace App\Policies;

use App\Models\Supply;
use App\Models\User;

class SupplyPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool { return false; }
    public function view(User $user, Supply $supply): bool { return false; }
    public function create(User $user): bool { return false; }
    public function update(User $user, Supply $supply): bool { return false; }
    public function delete(User $user, Supply $supply): bool { return false; }
}
