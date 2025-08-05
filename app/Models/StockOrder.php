<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockOrder extends Model
{
    use HasFactory;
    protected $fillable = ['truck_id', 'warehouse_id', 'status', 'ordered_at'];

    public function truck(): BelongsTo {
        return $this->belongsTo(Truck::class);
    }
    public function warehouse(): BelongsTo {
        return $this->belongsTo(Warehouse::class);
    }
    public function items(): HasMany {
        return $this->hasMany(StockOrderItem::class);
    }
}
