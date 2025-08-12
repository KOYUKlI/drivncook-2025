<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOrderItem extends Model {
    use HasFactory, HasUlidRouteKey;
    protected $fillable = ['stock_order_id', 'supply_id', 'quantity', 'unit_price', 'ulid'];

    public function stockOrder(): BelongsTo {
        return $this->belongsTo(StockOrder::class);
    }
    public function supply(): BelongsTo {
        return $this->belongsTo(Supply::class);
    }
}
