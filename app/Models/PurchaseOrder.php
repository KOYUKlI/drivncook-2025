<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'warehouse_id',
        'franchisee_id',
        'status',
        'corp_ratio_cached',
        'shipping_date',
        'tracking_number',
        'carrier',
        'preparation_notes',
        'shipping_notes',
        'reception_notes',
    ];

    protected $casts = [
        'corp_ratio_cached' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'shipping_date' => 'datetime',
    ];

    /**
     * The franchisee for this purchase order.
     */
    public function franchisee(): BelongsTo
    {
        return $this->belongsTo(Franchisee::class);
    }

    /**
     * The warehouse for this purchase order.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Purchase order lines.
     */
    public function lines(): HasMany
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }
}
