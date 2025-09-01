<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PurchaseOrderLine extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'purchase_lines';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', 'purchase_order_id', 'stock_item_id', 'qty', 'qty_picked', 'qty_shipped', 'qty_delivered',
        'unit_price_cents', 'received_qty',
    ];

    protected $casts = [
        'qty' => 'integer',
    'received_qty' => 'integer',
    'qty_picked' => 'integer',
    'qty_shipped' => 'integer',
    'qty_delivered' => 'integer',
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
