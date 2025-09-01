<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckOwnership extends Model
{
    use HasFactory;

    protected $table = 'truck_ownerships';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'truck_id',
        'franchisee_id',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function franchisee(): BelongsTo
    {
        return $this->belongsTo(Franchisee::class);
    }
}
