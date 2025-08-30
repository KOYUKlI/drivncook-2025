<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FranchiseApplication extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'full_name',
        'email',
        'phone',
        'company_name',
        'desired_area',
        'entry_fee_ack',
        'royalty_ack',
        'central80_ack',
        'status',
        'notes',
    ];

    protected $casts = [
        'entry_fee_ack' => 'boolean',
        'royalty_ack' => 'boolean',
        'central80_ack' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The applicant (User) for this application.
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Documents attached to this application.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(FranchiseApplicationDocument::class);
    }

    /**
     * Events/timeline for this application.
     */
    public function events(): HasMany
    {
        return $this->hasMany(FranchiseApplicationEvent::class)->orderBy('created_at', 'desc');
    }
}
