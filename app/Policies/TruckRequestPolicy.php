<?php

namespace App\Policies;

use App\Models\TruckRequest;
use App\Models\User;

class TruckRequestPolicy
{
    public function before(User $user, string $ability): bool|null
    { return $user->role === 'admin' ? true : null; }

    public function viewAny(User $user): bool { return $user->role === 'franchise'; }
    public function view(User $user, TruckRequest $req): bool { return $user->role==='franchise' && $req->franchise_id === $user->franchise_id; }
    public function create(User $user): bool { return $user->role === 'franchise'; }
    public function update(User $user, TruckRequest $req): bool { return $this->view($user, $req); }
    public function delete(User $user, TruckRequest $req): bool { return false; }
}
