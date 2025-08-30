<?php

namespace App\Policies;

use App\Models\MaintenanceLog;
use App\Models\User;

class MaintenanceLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    public function view(User $user, MaintenanceLog $log): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
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
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can download maintenance log attachments.
     */
    public function downloadAttachment(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }
}
