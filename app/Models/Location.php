<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $fillable = [
        'label', 'address', 'city', 'postal_code', 'lat', 'lng', 'ulid'
    ];

    public function deployments(): HasMany
    {
        return $this->hasMany(TruckDeployment::class);
    }
}
