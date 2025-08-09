<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supply extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $fillable = ['name', 'sku', 'unit', 'cost', 'ulid'];

    public function stockOrderItems(): HasMany {
        return $this->hasMany(StockOrderItem::class);
    }
}
