<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyCard extends Model {
    use HasFactory;
    protected $fillable = ['code', 'points'];

    public function customerOrders(): HasMany {
        return $this->hasMany(CustomerOrder::class);
    }
}
