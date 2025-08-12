<?php

namespace App\Policies;

use App\Models\ComplianceKpi;
use App\Models\User;

class ComplianceKpiPolicy
{
    public function before(User $user, string $ability): bool|null { return $user->role==='admin'?true:null; }
    public function viewAny(User $user): bool { return $user->role==='franchise'; }
    public function view(User $user, ComplianceKpi $k): bool { return $user->role==='franchise' && $k->franchise_id === $user->franchise_id; }
}
