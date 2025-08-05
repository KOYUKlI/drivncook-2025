<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model {
    use HasFactory;
    protected $fillable = ['customer_order_id', 'dish_id', 'quantity', 'price'];

    public function customerOrder(): BelongsTo {
        return $this->belongsTo(CustomerOrder::class);
    }
    public function dish(): BelongsTo {
        return $this->belongsTo(Dish::class);
    }
}
