<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $fillable = ['warehouse_id', 'supply_id', 'on_hand'];

    public function warehouse(): BelongsTo { return $this->belongsTo(Warehouse::class); }
    public function supply(): BelongsTo { return $this->belongsTo(Supply::class); }
}
