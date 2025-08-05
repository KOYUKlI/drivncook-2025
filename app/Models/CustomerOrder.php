<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerOrder extends Model {
    use HasFactory;
    protected $fillable = ['truck_id', 'loyalty_card_id', 'total_price', 'ordered_at'];

    public function truck(): BelongsTo {
        return $this->belongsTo(Truck::class);
    }
    public function loyaltyCard(): BelongsTo {
        return $this->belongsTo(LoyaltyCard::class);
    }
    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }
}
