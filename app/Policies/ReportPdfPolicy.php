<?php

namespace App\Policies;

use App\Models\ReportPdf;
use App\Models\User;

class ReportPdfPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse', 'franchisee']);
    }

    public function view(User $user, $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'warehouse']);
    }

    public function delete(User $user, $model): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Allow downloading a generated report PDF.
     * Admin and warehouse can download any; a franchisee can download only their own report.
     */
    public function downloadReport(User $user, ReportPdf $model): bool
    {
        if ($user->hasAnyRole(['admin', 'warehouse'])) {
            return true;
        }

        if ($user->hasRole('franchisee')) {
            return !empty($model->franchisee_id) && $user->franchisee_id === $model->franchisee_id;
        }

        return false;
    }
}
