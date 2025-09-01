<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockMovementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any stock movements.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can view the stock movement.
     */
    public function view(User $user, StockMovement $stockMovement): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can create stock movements.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }
}
