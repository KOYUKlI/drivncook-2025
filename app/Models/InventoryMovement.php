<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $fillable = ['inventory_id','type','qty','reason','ref_table','ref_id','created_at','ulid'];

    public $timestamps = false;

    protected $casts = [
        'qty' => 'decimal:3',
        'created_at' => 'datetime',
    ];

    public function inventory(): BelongsTo { return $this->belongsTo(Inventory::class); }
}
