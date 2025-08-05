<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model {
    use HasFactory;
    protected $fillable = ['event_id', 'truck_id'];

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
    public function truck(): BelongsTo {
        return $this->belongsTo(Truck::class);
    }
}
