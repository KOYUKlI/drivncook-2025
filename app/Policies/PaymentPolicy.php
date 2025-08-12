<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function before(User $user, string $ability): bool|null { return $user->role==='admin'?true:null; }
    public function viewAny(User $user): bool { return in_array($user->role,['franchise']); }
    public function view(User $user, Payment $p): bool { return $user->role==='franchise' ? ($p->order && $p->order->truck && $p->order->truck->franchise_id===$user->franchise_id) : false; }
    public function create(User $user): bool { return in_array($user->role,['franchise']); }
}
