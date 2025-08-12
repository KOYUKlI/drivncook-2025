<?php

namespace App\Policies;

use App\Models\CustomerOrder;
use App\Models\User;

class CustomerOrderPolicy
{
    public function before(User $user, string $ability): bool|null
    { return $user->role === 'admin' ? true : null; }

    public function viewAny(User $user): bool { return in_array($user->role,['franchise','customer']); }
    public function view(User $user, CustomerOrder $order): bool {
        if ($user->role === 'franchise') return $order->truck && $order->truck->franchise_id === $user->franchise_id;
        if ($user->role === 'customer') return $order->customer_id === $user->id;
        return false;
    }
    public function create(User $user): bool { return in_array($user->role,['franchise','customer']); }
    public function update(User $user, CustomerOrder $order): bool { return $this->view($user,$order); }
    public function delete(User $user, CustomerOrder $order): bool { return $user->role==='franchise' && $this->view($user,$order); }
}
