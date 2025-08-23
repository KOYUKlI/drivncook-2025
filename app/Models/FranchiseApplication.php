<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'city',
        'budget',
        'experience',
        'motivation',
    'accept_entry_fee',
    'accept_royalty',
        'status',
        'reviewed_at',
    'entry_fee_due',
    'entry_fee_status',
    'entry_fee_paid_at',
    'stripe_session_id',
    'stripe_payment_intent',
    ];
}
