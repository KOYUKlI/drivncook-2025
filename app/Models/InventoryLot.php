<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'lot_code',
        'expires_at',
        'qty',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'qty' => 'decimal:3',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
