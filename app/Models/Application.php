<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'email',
        'territory',
        'status',
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
