<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DishIngredient extends Model
{
    use HasFactory;

    protected $fillable = ['dish_id', 'supply_id', 'qty_per_dish', 'unit'];

    public function dish(): BelongsTo { return $this->belongsTo(Dish::class); }
    public function supply(): BelongsTo { return $this->belongsTo(Supply::class); }
}
