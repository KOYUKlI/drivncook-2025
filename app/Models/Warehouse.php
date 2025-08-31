<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', 'code', 'name', 'city', 'region', 'address', 'phone', 'email', 'is_active', 'notes'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    
    public function inventory(): HasMany
    {
        return $this->hasMany(WarehouseInventory::class);
    }
    
    public function getLowStockCountAttribute(): int
    {
        return $this->inventory()
            ->whereRaw('qty_on_hand <= min_qty')
            ->count();
    }
    
    public function getStockItemsCountAttribute(): int
    {
        return $this->inventory()->count();
    }
}
