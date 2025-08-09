<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model {
    use HasFactory;
    protected $fillable = ['truck_id', 'maintenance_date', 'description', 'cost'];

    public function truck(): BelongsTo {
    return $this->belongsTo(Truck::class);
    }
}
