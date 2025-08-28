<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', 'sku', 'name', 'unit', 'price_cents', 'is_central',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'is_central' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
