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
        if ($user->hasAnyRole(['admin', 'warehouse'])) {
            return true;
        }
        // Franchisee can view own orders
        if ($user->hasRole('franchisee')) {
            try {
                $fr = $user->franchisee; // relation by email
                if ($fr && $purchaseOrder && (string) $purchaseOrder->franchisee_id === (string) $fr->id) {
                    // For FO order requests, restrict to Draft/Submitted; other kinds remain viewable
                    if (isset($purchaseOrder->kind) && $purchaseOrder->kind === 'franchisee_po') {
                        return in_array($purchaseOrder->status, ['Draft','Submitted']);
                    }
                    return true;
                }
            } catch (\Throwable $e) {
                return false;
            }
        }
        return false;
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

    public function update(User $user, $purchaseOrder): bool
    {
        if ($user->hasAnyRole(['admin','warehouse'])) { return true; }
        if ($user->hasRole('franchisee')) {
            $fr = $user->franchisee;
            if ($fr && (string)$purchaseOrder->franchisee_id === (string)$fr->id) {
                return in_array($purchaseOrder->status, ['Draft','Submitted']);
            }
        }
        return false;
    }

    public function submit(User $user, $purchaseOrder): bool
    {
        if (!$user->hasRole('franchisee')) { return false; }
        $fr = $user->franchisee;
        return $fr && (string)$purchaseOrder->franchisee_id === (string)$fr->id && $purchaseOrder->status === 'Draft';
    }

    public function approve(User $user, $purchaseOrder): bool
    { return $user->hasAnyRole(['admin','warehouse']); }

    public function pick(User $user, $purchaseOrder): bool
    { return $user->hasAnyRole(['admin','warehouse']); }

    public function ship(User $user, $purchaseOrder): bool
    { return $user->hasAnyRole(['admin','warehouse']); }

    public function deliver(User $user, $purchaseOrder): bool
    { return $user->hasAnyRole(['admin','warehouse']); }

    public function close(User $user, $purchaseOrder): bool
    { return $user->hasAnyRole(['admin','warehouse']); }

    public function cancel(User $user, $purchaseOrder): bool
    { return $user->hasAnyRole(['admin','warehouse']); }
}
