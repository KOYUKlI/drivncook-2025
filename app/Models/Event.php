<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model {
    use HasFactory;
    protected $fillable = ['name', 'date', 'location', 'description'];

    public function registrations(): HasMany {
        return $this->hasMany(EventRegistration::class);
    }
    public function trucks(): BelongsToMany {
        return $this->belongsToMany(Truck::class, 'event_registrations');
    }
}
