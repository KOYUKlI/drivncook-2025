<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Franchisee extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'billing_address',
        'royalty_rate',
    'status',
    ];

    protected $casts = [
        'royalty_rate' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The user associated with this franchisee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Trucks assigned to this franchisee.
     */
    public function trucks(): HasMany
    {
        return $this->hasMany(Truck::class);
    }

    /**
     * Sales made by this franchisee.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Purchase orders created by this franchisee.
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'creator_id', 'user_id');
    }

    /**
     * Accessor: Map DB status (e.g., Active, Draft, Retired) to UI status keys (active, pending, inactive).
     */
    public function getUiStatusAttribute(): string
    {
        $db = $this->attributes['status'] ?? null;
        return match ($db) {
            'Active' => 'active',
            'Draft' => 'pending',
            'InMaintenance' => 'pending',
            'Retired' => 'inactive',
            default => $db ? strtolower($db) : 'inactive',
        };
    }

    /**
     * Mutator: Accept UI status values and map them to DB enum values.
     */
    public function setStatusAttribute($value): void
    {
        if (is_string($value)) {
            $map = [
                'active' => 'Active',
                'inactive' => 'Retired',
                'pending' => 'Draft',
            ];
            $this->attributes['status'] = $map[strtolower($value)] ?? $value;
            return;
        }

        $this->attributes['status'] = $value;
    }
}
