<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supply extends Model {
    use HasFactory;
    protected $fillable = ['name', 'sku', 'unit', 'cost'];

    public function stockOrderItems(): HasMany {
        return $this->hasMany(StockOrderItem::class);
    }
}
