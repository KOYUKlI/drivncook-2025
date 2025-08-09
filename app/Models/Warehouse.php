<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model {
    use HasFactory, HasUlidRouteKey;

    protected $fillable = ['name', 'location', 'franchise_id', 'ulid'];

    public function franchise(): BelongsTo {
        return $this->belongsTo(Franchise::class);
    }
    public function stockOrders(): HasMany {
        return $this->hasMany(StockOrder::class);
    }
}
