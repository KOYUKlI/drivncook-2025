<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any sales.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'franchisee']);
    }

    /**
     * Determine whether the user can create sales.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('franchisee');
    }

    /**
     * Determine whether the user can view the sale.
     */
    public function view(User $user, $sale = null): bool
    {
        // Admin can view all sales
        if ($user->hasRole('admin')) {
            return true;
        }

        // Franchisee can only view their own sales
        if ($user->hasRole('franchisee')) {
            // In real app: check if sale belongs to user's franchise
            return true; // Simplified for demo
        }

        return false;
    }

    /**
     * Determine whether the user can update the sale.
     */
    public function update(User $user, $sale = null): bool
    {
        // Only admin can update sales after creation
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the sale.
     */
    public function delete(User $user, $sale = null): bool
    {
        // Only admin can delete sales
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can generate sales reports.
     */
    public function generateReports(User $user): bool
    {
        return $user->hasRole(['admin', 'franchisee']);
    }
}
