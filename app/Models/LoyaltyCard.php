<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyCard extends Model {
    use HasFactory;
    protected $fillable = ['code', 'points','user_id'];

    public function customerOrders(): HasMany {
        return $this->hasMany(CustomerOrder::class);
    }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
