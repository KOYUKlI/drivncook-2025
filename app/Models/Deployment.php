<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Deployment extends Model
{
    use HasFactory;
    
    // Status constants
    public const STATUS_PLANNED = 'planned';
    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'truck_deployments';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 
        'truck_id', 
        'franchisee_id', 
        'location_text', 
        'geo_lat',
        'geo_lng',
        'planned_start_at', 
        'planned_end_at', 
        'actual_start_at', 
        'actual_end_at', 
        'status',
        'notes',
        'cancel_reason'
    ];

    protected $casts = [
        'planned_start_at' => 'datetime',
        'planned_end_at' => 'datetime',
        'actual_start_at' => 'datetime',
        'actual_end_at' => 'datetime',
        'geo_lat' => 'decimal:7',
        'geo_lng' => 'decimal:7',
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
    
    /**
     * Check if there's a conflict with other deployments for the same truck
     */
    public static function hasConflict(int $truckId, Carbon $startAt, Carbon $endAt, ?string $excludeId = null): bool
    {
        $query = self::query()
            ->where('truck_id', $truckId)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->where(function ($q) use ($startAt, $endAt) {
                // Check for overlap with existing deployments
                $q->where(function ($subQ) use ($startAt, $endAt) {
                    $subQ->where('planned_start_at', '<=', $endAt)
                         ->where('planned_end_at', '>=', $startAt);
                });
                
                // Also check for ongoing deployments
                $q->orWhere(function ($subQ) use ($startAt) {
                    $subQ->where('status', self::STATUS_OPEN)
                         ->where(function ($s) use ($startAt) {
                             $s->whereNull('planned_end_at')
                               ->orWhere('planned_end_at', '>=', $startAt);
                         });
                });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
    
    /**
     * Get conflicting deployments for the same truck
     */
    public static function getConflicts(int $truckId, Carbon $startAt, Carbon $endAt, ?string $excludeId = null)
    {
        $query = self::query()
            ->where('truck_id', $truckId)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->where(function ($q) use ($startAt, $endAt) {
                // Check for overlap with existing deployments
                $q->where(function ($subQ) use ($startAt, $endAt) {
                    $subQ->where('planned_start_at', '<=', $endAt)
                         ->where('planned_end_at', '>=', $startAt);
                });
                
                // Also check for ongoing deployments
                $q->orWhere(function ($subQ) use ($startAt) {
                    $subQ->where('status', self::STATUS_OPEN)
                         ->where(function ($s) use ($startAt) {
                             $s->whereNull('planned_end_at')
                               ->orWhere('planned_end_at', '>=', $startAt);
                         });
                });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->get();
    }
    
    /**
     * Calculate truck utilization for the last 30 days
     */
    public static function calculateUtilization(int $truckId, int $days = 30): float
    {
        $startDate = now()->subDays($days);
        $endDate = now();
        
        // Total hours in the period
        $totalHours = $days * 24;
        
        // Sum of actual deployment hours
        $deployments = self::where('truck_id', $truckId)
            ->whereIn('status', [self::STATUS_OPEN, self::STATUS_CLOSED])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('actual_start_at', [$startDate, $endDate])
                      ->orWhereBetween('actual_end_at', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('actual_start_at', '<', $startDate)
                            ->where('actual_end_at', '>', $endDate);
                      });
            })
            ->get();
            
        $utilizationHours = 0;
        
        foreach ($deployments as $deployment) {
            $start = max($deployment->actual_start_at, $startDate);
            $end = $deployment->status === self::STATUS_CLOSED 
                ? min($deployment->actual_end_at, $endDate) 
                : $endDate;
                
            $utilizationHours += $start->diffInHours($end);
        }
        
        return min(100, round(($utilizationHours / $totalHours) * 100, 1));
    }
    
    /**
     * Get status color for UI display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PLANNED => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_OPEN => 'bg-amber-100 text-amber-800 border-amber-200',
            self::STATUS_CLOSED => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800 border-gray-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
}
