<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FranchiseApplicationEvent extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'franchise_application_id',
        'user_id',
        'from_status',
        'to_status',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The franchise application this event belongs to.
     */
    public function franchiseApplication(): BelongsTo
    {
        return $this->belongsTo(FranchiseApplication::class);
    }

    /**
     * The user who triggered this event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
