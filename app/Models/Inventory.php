<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $table = 'inventory';
    protected $fillable = ['warehouse_id', 'supply_id', 'on_hand', 'ulid'];

    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function supply(): BelongsTo { return $this->belongsTo(Supply::class); }
    public function movements() { return $this->hasMany(InventoryMovement::class); }
}
