<?php

namespace App\Policies;

use App\Models\Commission;
use App\Models\User;

class CommissionPolicy
{
    public function before(User $user, string $ability): bool|null { return $user->role==='admin'?true:null; }
    public function viewAny(User $user): bool { return $user->role==='franchise'; }
    public function view(User $user, Commission $c): bool { return $user->role==='franchise' && $c->franchisee_id === $user->id; }
}
