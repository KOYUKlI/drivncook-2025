<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TruckDeployment extends Model
{
    use HasFactory;

    protected $table = 'truck_deployments';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'truck_id',
        'franchisee_id',
        'location_text',
        'planned_start_at',
        'planned_end_at',
        'actual_start_at',
        'actual_end_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'planned_start_at' => 'datetime',
        'planned_end_at' => 'datetime',
        'actual_start_at' => 'datetime',
        'actual_end_at' => 'datetime',
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

    public function franchisee(): BelongsTo
    {
        return $this->belongsTo(Franchisee::class);
    }
}
