<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class WarehouseInventory extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
    'id', 'warehouse_id', 'stock_item_id', 'qty_on_hand', 'min_qty', 'max_qty'
    ];

    protected $casts = [
        'qty_on_hand' => 'integer',
        'min_qty' => 'integer',
        'max_qty' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class);
    }

    public function isLowStock(): bool
    {
        if ($this->min_qty === null) {
            return false;
        }
        
        return $this->qty_on_hand <= $this->min_qty;
    }
}
