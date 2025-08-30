<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderLine extends Model
{
    use HasFactory;

    protected $table = 'purchase_lines';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', 'purchase_order_id', 'stock_item_id', 'qty', 'unit_price_cents',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price_cents' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class);
    }
}
