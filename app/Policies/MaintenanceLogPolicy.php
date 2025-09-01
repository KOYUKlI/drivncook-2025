<?php

namespace App\Policies;

use App\Models\MaintenanceLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MaintenanceLogPolicy
{
    public function viewAny(User $user): bool
    {
    // BO roles can view all; franchisees can view their own via controller scoping
    return $user->hasAnyRole(['admin', 'fleet', 'franchisee']);
    }

    public function view(User $user, MaintenanceLog $log): bool
    {
        if ($user->hasAnyRole(['admin', 'fleet'])) {
            return true;
        }

        // Franchisee: only if the maintenance log belongs to their assigned truck
        if ($user->hasRole('franchisee') && $user->franchisee) {
            $truck = $log->truck;
            return $truck && ($truck->franchisee_id === $user->franchisee->id
                || $truck->ownerships()->whereNull('ended_at')->where('franchisee_id', $user->franchisee->id)->exists());
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    public function update(User $user, MaintenanceLog $log): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can schedule maintenance.
     */
    public function schedule(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can open the maintenance log.
     */
    public function open(User $user, MaintenanceLog $log): bool
    {
        if (!$user->hasAnyRole(['admin', 'fleet'])) {
            return false;
        }

        return $log->status === MaintenanceLog::STATUS_PLANNED;
    }

    /**
     * Determine whether the user can pause the maintenance log.
     */
    public function pause(User $user, MaintenanceLog $log): bool
    {
        if (!$user->hasAnyRole(['admin', 'fleet'])) {
            return false;
        }

        return $log->status === MaintenanceLog::STATUS_OPEN;
    }

    /**
     * Determine whether the user can resume the maintenance log.
     */
    public function resume(User $user, MaintenanceLog $log): bool
    {
        if (!$user->hasAnyRole(['admin', 'fleet'])) {
            return false;
        }

        return $log->status === MaintenanceLog::STATUS_PAUSED;
    }

    public function close(User $user, MaintenanceLog $log): bool
    {
        if (!$user->hasAnyRole(['admin', 'fleet'])) {
            return false;
        }

        return in_array($log->status, [
            MaintenanceLog::STATUS_OPEN, 
            MaintenanceLog::STATUS_PAUSED
        ]);
    }
    
    /**
     * Determine whether the user can cancel the maintenance log.
     */
    public function cancel(User $user, MaintenanceLog $log): bool
    {
        if (!$user->hasAnyRole(['admin', 'fleet'])) {
            return false;
        }

        return in_array($log->status, [
            MaintenanceLog::STATUS_PLANNED, 
            MaintenanceLog::STATUS_OPEN, 
            MaintenanceLog::STATUS_PAUSED
        ]);
    }

    /**
     * Determine whether the user can add attachments to the maintenance log.
     */
    public function addAttachment(User $user, MaintenanceLog $log): bool
    {
        if (!$user->hasAnyRole(['admin', 'fleet'])) {
            return false;
        }

        return in_array($log->status, [
            MaintenanceLog::STATUS_PLANNED, 
            MaintenanceLog::STATUS_OPEN, 
            MaintenanceLog::STATUS_PAUSED,
            MaintenanceLog::STATUS_CLOSED
        ]);
    }

    /**
     * Determine whether the user can view maintenance log attachments.
     */
    public function viewAttachment(User $user): bool
    {
    return $user->hasAnyRole(['admin', 'fleet', 'franchisee']);
    }

    /**
     * Determine whether the user can download maintenance log attachments.
     */
    public function downloadAttachment(User $user): bool
    {
    return $user->hasAnyRole(['admin', 'fleet', 'franchisee']);
    }
}
