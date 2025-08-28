<?php

namespace App\Policies;

use App\Models\User;

class PurchaseOrderPolicy
{
    /**
     * Determine whether the user can view any purchase orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can view the purchase order.
     */
    public function view(User $user, $purchaseOrder): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can validate compliance.
     */
    public function validateCompliance(User $user, $purchaseOrder): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can update central ratio.
     */
    public function updateRatio(User $user, $purchaseOrder): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can recalculate ratios.
     */
    public function recalculate(User $user, $purchaseOrder): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can view compliance reports.
     */
    public function viewComplianceReport(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    /** Create allowed to franchisee (own), admin, warehouse. */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['franchisee', 'admin', 'warehouse']);
    }

    /** UpdateStatus allowed to warehouse and admin */
    public function updateStatus(User $user, $purchaseOrder): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }
}
