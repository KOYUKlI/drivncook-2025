<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dish extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $fillable = ['name', 'description', 'price', 'ulid'];

    public function orderItems(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    public function ingredients(): HasMany {
        return $this->hasMany(DishIngredient::class);
    }
}
