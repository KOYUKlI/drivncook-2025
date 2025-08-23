<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckRequest extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $fillable = [
        'franchise_id',
        'requested_by',
        'status', // pending|approved|rejected
        'reason',
        'admin_note',
        'handled_by',
        'handled_at',
        'ulid',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    public function franchise(): BelongsTo
    { return $this->belongsTo(Franchise::class); }

    public function requester(): BelongsTo
    { return $this->belongsTo(User::class, 'requested_by'); }

    public function handler(): BelongsTo
    { return $this->belongsTo(User::class, 'handled_by'); }
}
