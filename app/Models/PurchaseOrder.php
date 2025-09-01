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
    'reference',
    'warehouse_id',
    'franchisee_id',
    'placed_by',
    'status',
    'kind',
    'corp_ratio_cached',
    'shipping_date',
    'tracking_number',
    'carrier',
    'preparation_notes',
    'shipping_notes',
    'reception_notes',
    'status_updated_at',
    'status_updated_by',
    'shipped_at',
    'delivered_at',
    ];

    protected $casts = [
        'corp_ratio_cached' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    'shipping_date' => 'datetime',
    'status_updated_at' => 'datetime',
    'shipped_at' => 'datetime',
    'delivered_at' => 'datetime',
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

    public function scopeReplenishments($q)
    {
        return $q->where('kind', 'Replenishment');
    }

    public static function nextReference(): string
    {
        $prefix = 'REP-'.now()->format('Ym').'-';
        $seq = 1;
        do {
            $candidate = $prefix.str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
            $exists = static::where('reference', $candidate)->exists();
            $seq++;
        } while ($exists && $seq < 10000);
        return $candidate;
    }
}
