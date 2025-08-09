<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchisee_id',
        'period_year',
        'period_month',
        'turnover',
        'rate',
        'status',
        'calculated_at',
        'paid_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function franchisee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'franchisee_id');
    }
}
