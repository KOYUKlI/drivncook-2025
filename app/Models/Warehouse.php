<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model {
    use HasFactory;
    protected $fillable = ['name', 'location', 'franchise_id'];

    public function franchise(): BelongsTo {
        return $this->belongsTo(Franchise::class);
    }
    public function stockOrders(): HasMany {
        return $this->hasMany(StockOrder::class);
    }
}
