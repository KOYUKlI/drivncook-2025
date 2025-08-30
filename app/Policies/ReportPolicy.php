<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any reports.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'warehouse', 'fleet', 'franchisee']);
    }

    /**
     * Determine whether the user can generate sales reports.
     */
    public function generateSalesReports(User $user): bool
    {
        return $user->hasRole(['admin', 'franchisee']);
    }

    /**
     * Determine whether the user can generate purchase order reports.
     */
    public function generatePurchaseOrderReports(User $user): bool
    {
        return $user->hasRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can generate truck reports.
     */
    public function generateTruckReports(User $user): bool
    {
        return $user->hasRole(['admin', 'fleet']);
    }

    /**
     * Determine whether the user can generate compliance reports.
     */
    public function generateComplianceReports(User $user): bool
    {
        return $user->hasRole(['admin', 'warehouse']);
    }

    /**
     * Determine whether the user can view global analytics.
     */
    public function viewGlobalAnalytics(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can export reports in any format.
     */
    public function exportReports(User $user): bool
    {
        return $user->hasRole(['admin', 'warehouse', 'fleet', 'franchisee']);
    }

    /**
     * Determine whether the user can access historical data.
     */
    public function accessHistoricalData(User $user): bool
    {
        return $user->hasRole(['admin', 'warehouse', 'fleet']);
    }

    /**
     * Determine whether the user can download a specific report.
     */
    public function downloadReport(User $user, $report): bool
    {
        // Admin and warehouse can download any report
        if ($user->hasRole(['admin', 'warehouse'])) {
            return true;
        }

        // Franchisees can only download their own reports
        if ($user->hasRole('franchisee') && $user->franchisee_id) {
            return $report->franchisee_id === $user->franchisee_id;
        }

        return false;
    }
}
