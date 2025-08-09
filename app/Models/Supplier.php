<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $fillable = [
        'name',
        'siret',
        'contact_email',
        'phone',
        'is_active',
    ];
}
