<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Truck extends Model {
    use HasFactory;
    protected $fillable = ['name', 'license_plate', 'franchise_id'];

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
    public function events(): BelongsToMany {
        // Many-to-many: a truck can register for many events
        return $this->belongsToMany(Event::class, 'event_registrations');
    }
}
