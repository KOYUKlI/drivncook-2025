<?php

namespace App\Policies;

use App\Models\TruckDeployment;
use App\Models\User;

class TruckDeploymentPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(['admin','fleet']); }
    public function view(User $user, TruckDeployment $deployment): bool { return $user->hasRole(['admin','fleet']); }
    public function create(User $user): bool { return $user->hasRole(['admin','fleet']); }
    public function update(User $user, TruckDeployment $deployment): bool { return $user->hasRole(['admin','fleet']); }
    public function open(User $user, TruckDeployment $deployment): bool { return $user->hasRole(['admin','fleet']); }
    public function close(User $user, TruckDeployment $deployment): bool { return $user->hasRole(['admin','fleet']); }
    public function cancel(User $user, TruckDeployment $deployment): bool { return $user->hasRole(['admin','fleet']); }
}
