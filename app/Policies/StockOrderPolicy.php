<?php

namespace App\Policies;

use App\Models\StockOrder;
use App\Models\User;

class StockOrderPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool { return in_array($user->role, ['admin','franchise']); }

    public function view(User $user, StockOrder $order): bool
    {
        return $user->role === 'franchise' ? ($order->truck && $order->truck->franchise_id === $user->franchise_id) : false;
    }

    public function create(User $user): bool { return in_array($user->role, ['admin','franchise']); }

    public function update(User $user, StockOrder $order): bool
    {
        return $user->role === 'franchise' ? ($order->truck && $order->truck->franchise_id === $user->franchise_id) : false;
    }

    public function delete(User $user, StockOrder $order): bool
    {
        return $user->role === 'franchise' ? ($order->truck && $order->truck->franchise_id === $user->franchise_id) : false;
    }
}
