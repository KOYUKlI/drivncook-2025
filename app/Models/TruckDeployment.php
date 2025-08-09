<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckDeployment extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $fillable = [
        'truck_id', 'location_id', 'starts_at', 'ends_at', 'ulid'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function truck(): BelongsTo { return $this->belongsTo(Truck::class); }
    public function location(): BelongsTo { return $this->belongsTo(Location::class); }
}
