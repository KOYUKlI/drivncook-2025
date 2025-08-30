<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $table = 'maintenance_logs';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'truck_id',
    'type', // new schema
    'kind', // legacy schema
        'status',
    'opened_at', // new schema
    'started_at', // legacy schema
        'closed_at',
        'opened_by',
        'closed_by',
        'description',
        'resolution',
        'cost_cents',
        'attachment_path',
    ];

    protected $casts = [
    'opened_at' => 'datetime',
    'started_at' => 'datetime',
        'closed_at' => 'datetime',
        'cost_cents' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
