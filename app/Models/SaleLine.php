<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleLine extends Model
{
    use HasFactory;

    protected $table = 'sale_lines';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [ 'id', 'sale_id', 'stock_item_id', 'qty', 'unit_price_cents' ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price_cents' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sale(): BelongsTo { return $this->belongsTo(Sale::class); }
}
