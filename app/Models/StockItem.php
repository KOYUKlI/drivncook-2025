<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', 'sku', 'name', 'unit', 'price_cents', 'is_central', 'is_active', 'notes'
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'is_central' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function purchaseLines(): HasMany
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }
    
    public function saleLines(): HasMany
    {
        return $this->hasMany(SaleLine::class);
    }
    
    public function warehouseInventory(): HasMany
    {
        return $this->hasMany(WarehouseInventory::class);
    }
}
