<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAdjustment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'inventory_id',
        'qty_diff',
        'reason',
        'note',
        'created_at',
    ];

    protected $casts = [
        'qty_diff' => 'decimal:3',
        'created_at' => 'datetime',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
