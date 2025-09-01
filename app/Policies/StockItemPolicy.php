<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StockItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any stock items.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can view the stock item.
     */
    public function view(User $user, StockItem $stockItem): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can create stock items.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can update the stock item.
     */
    public function update(User $user, StockItem $stockItem): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can delete the stock item.
     */
    public function delete(User $user, StockItem $stockItem): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }
}
