<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Truck;

class TruckPolicy
{
    /**
     * Determine whether the user can view any trucks.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can view the truck.
     */
    public function view(User $user, Truck $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can create trucks.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can update the truck.
     */
    public function update(User $user, Truck $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can delete the truck.
     */
    public function delete(User $user, Truck $truck): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can schedule deployments.
     */
    public function scheduleDeployment(User $user, $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can open deployments.
     */
    public function openDeployment(User $user, $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can close deployments.
     */
    public function closeDeployment(User $user, $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can schedule maintenance.
     */
    public function scheduleMaintenance(User $user, $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can open maintenance sessions.
     */
    public function openMaintenance(User $user, $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can close maintenance sessions.
     */
    public function closeMaintenance(User $user, $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can update truck status.
     */
    public function updateStatus(User $user, $truck): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can view utilization reports.
     */
    public function viewUtilizationReport(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'fleet']);
    }
}
