<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyRule extends Model
{
    use HasFactory;
    protected $fillable = ['points_per_euro','redeem_rate','expires_after_months','active'];
    protected $casts = [
        'points_per_euro'=>'decimal:2',
        'redeem_rate'=>'decimal:2',
        'active'=>'boolean'
    ];
}
