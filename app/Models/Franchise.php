<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class Franchise extends Model {
    use HasFactory;
    protected $fillable = ['name'];

    // Relations:
    public function trucks(): HasMany {
        return $this->hasMany(Truck::class);
    }
    public function warehouses(): HasMany {
        return $this->hasMany(Warehouse::class);
    }
    public function users(): HasMany {
        return $this->hasMany(User::class);
    }
    public function stockOrders(): HasManyThrough {
        // All stock orders for this franchise through its trucks
        return $this->hasManyThrough(StockOrder::class, Truck::class);
    }
    public function customerOrders(): HasManyThrough {
        // All customer orders for this franchise through its trucks
        return $this->hasManyThrough(CustomerOrder::class, Truck::class);
    }
}
