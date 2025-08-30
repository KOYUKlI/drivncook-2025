<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class Truck extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'plate',
        'vin',
        'make',
        'model',
        'year',
        'status',
        'acquired_at',
        'service_start',
        'mileage_km',
        'franchisee_id',
        'notes',
        'registration_doc_path',
        'insurance_doc_path',
    ];

    protected $casts = [
        'acquired_at' => 'date',
        'service_start' => 'date',
        'year' => 'integer',
        'mileage_km' => 'integer',
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
            if (empty($model->code)) {
                // Generate a simple sequential-like code based on ulid tail to avoid race without extra table
                $suffix = strtoupper(substr(Str::ulid()->toBase32(), -4));
                $model->code = 'TRK-' . $suffix;
            }
        });
    }

    /**
     * The franchisee who owns this truck.
     */
    public function franchisee(): BelongsTo
    {
        return $this->belongsTo(Franchisee::class);
    }

    /**
     * Sales made with this truck.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Deployments for this truck.
     */
    public function deployments(): HasMany
    {
        // Prefer new TruckDeployment model if table exists; otherwise fallback to legacy Deployment
        if (class_exists(\App\Models\TruckDeployment::class) && Schema::hasTable('truck_deployments')) {
            return $this->hasMany(\App\Models\TruckDeployment::class);
        }
        // Legacy deployments table
        return $this->hasMany(Deployment::class);
    }

    /**
     * Maintenance logs for this truck.
     */
    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForFranchisee($query, string $franchiseeId)
    {
        return $query->where('franchisee_id', $franchiseeId);
    }

    // Accessors for UI mapping (align with ui.status.*)
    public function getUiStatusAttribute(): string
    {
        return [
            'Active' => 'active',
            'InMaintenance' => 'in_maintenance',
            'Retired' => 'retired',
            'Draft' => 'draft',
        ][$this->status] ?? 'draft';
    }

    public function setUiStatusAttribute(string $value): void
    {
        $map = [
            'active' => 'Active',
            'in_maintenance' => 'InMaintenance',
            'retired' => 'Retired',
            'draft' => 'Draft',
        ];
        $this->attributes['status'] = $map[$value] ?? 'Draft';
    }
}
