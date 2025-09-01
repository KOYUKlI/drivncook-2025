<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportPdf extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'franchisee_id',
        'type',
        'year',
        'month',
        'storage_path',
        'generated_at',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'generated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function franchisee(): BelongsTo
    {
        return $this->belongsTo(Franchisee::class);
    }
}
