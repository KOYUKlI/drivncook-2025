<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'city',
        'budget',
        'experience',
        'motivation',
        'status',
        'reviewed_at',
    ];
}
