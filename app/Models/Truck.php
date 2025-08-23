<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Truck extends Model {
    use HasFactory, HasUlidRouteKey;

    protected $fillable = ['name', 'license_plate', 'franchise_id', 'ulid'];

    public function franchise(): BelongsTo {
        return $this->belongsTo(Franchise::class);
    }
    public function maintenanceRecords(): HasMany {
        return $this->hasMany(MaintenanceRecord::class);
    }
    public function customerOrders(): HasMany {
        return $this->hasMany(CustomerOrder::class);
    }
    public function stockOrders(): HasMany {
        return $this->hasMany(StockOrder::class);
    }
    // Events feature removed for Mission 1 scope
}
