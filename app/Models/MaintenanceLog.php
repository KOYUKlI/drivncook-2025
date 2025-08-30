<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $table = 'maintenance_logs';

    protected $keyType = 'string';
    public $incrementing = false;
    
    /**
     * Maintenance status values
     */
    public const STATUS_PLANNED = 'planned';
    public const STATUS_OPEN = 'open';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELLED = 'cancelled';
    
    /**
     * Severity levels
     */
    public const SEVERITY_LOW = 'low';
    public const SEVERITY_MEDIUM = 'medium';
    public const SEVERITY_HIGH = 'high';
    
    /**
     * Priority levels
     */
    public const PRIORITY_P3 = 'P3';
    public const PRIORITY_P2 = 'P2';
    public const PRIORITY_P1 = 'P1';

    protected $fillable = [
        'id',
        'truck_id',
        'type', // new schema
        'kind', // legacy schema
        'status',
        'severity',
        'priority',
        'planned_start_at',
        'planned_end_at',
        'opened_at', // new schema
        'started_at', // legacy schema
        'paused_at',
        'resumed_at',
        'closed_at',
        'due_at',
        'opened_by',
        'closed_by',
        'description',
        'resolution',
        'cost_cents',
        'labor_cents',
        'parts_cents',
        'mileage_open_km',
        'mileage_close_km',
        'provider_name',
        'provider_contact',
        'attachment_path',
    ];

    protected $casts = [
        'planned_start_at' => 'datetime',
        'planned_end_at' => 'datetime',
        'opened_at' => 'datetime',
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
        'resumed_at' => 'datetime',
        'closed_at' => 'datetime',
        'due_at' => 'datetime',
        'cost_cents' => 'integer',
        'labor_cents' => 'integer',
        'parts_cents' => 'integer',
        'mileage_open_km' => 'integer',
        'mileage_close_km' => 'integer',
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
            
            // Set default values for new fields
            if (empty($model->status)) {
                $model->status = self::STATUS_PLANNED;
            }
            
            // Calculate total cost_cents from labor and parts if not set
            if (is_null($model->cost_cents) && (!is_null($model->labor_cents) || !is_null($model->parts_cents))) {
                $model->cost_cents = (int)$model->labor_cents + (int)$model->parts_cents;
            }
        });
        
        static::saving(function (self $model) {
            // Calculate total cost_cents from labor and parts on update
            if (!is_null($model->labor_cents) || !is_null($model->parts_cents)) {
                $model->cost_cents = (int)$model->labor_cents + (int)$model->parts_cents;
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
    
    /**
     * Get all attachments for this maintenance log
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(MaintenanceAttachment::class);
    }
    
    /**
     * Check if SLA is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->due_at) {
            return false;
        }
        
        if (in_array($this->status, [self::STATUS_CLOSED, self::STATUS_CANCELLED])) {
            return false;
        }
        
        return now() > $this->due_at;
    }
    
    /**
     * Get status color class for UI display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PLANNED => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_OPEN => 'bg-amber-100 text-amber-800 border-amber-200',
            self::STATUS_PAUSED => 'bg-purple-100 text-purple-800 border-purple-200',
            self::STATUS_CLOSED => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800 border-gray-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
    
    /**
     * Get severity color class for UI display
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            self::SEVERITY_LOW => 'bg-blue-100 text-blue-800',
            self::SEVERITY_MEDIUM => 'bg-amber-100 text-amber-800',
            self::SEVERITY_HIGH => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    /**
     * Get priority color class for UI display
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_P3 => 'bg-blue-100 text-blue-800',
            self::PRIORITY_P2 => 'bg-amber-100 text-amber-800',
            self::PRIORITY_P1 => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    /**
     * Get human readable time duration
     */
    public function getDurationAttribute(): ?string
    {
        if (!$this->opened_at || !$this->closed_at) {
            return null;
        }
        
        $start = $this->opened_at;
        $end = $this->closed_at;
        
        // Consider pauses if applicable
        if ($this->paused_at && $this->resumed_at) {
            $pauseDuration = $this->resumed_at->diffInSeconds($this->paused_at);
            $totalDuration = $end->diffInSeconds($start) - $pauseDuration;
        } else {
            $totalDuration = $end->diffInSeconds($start);
        }
        
        $hours = floor($totalDuration / 3600);
        $minutes = floor(($totalDuration % 3600) / 60);
        
        return "{$hours}h {$minutes}m";
    }
}
