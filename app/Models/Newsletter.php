<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Newsletter extends Model
{
    use HasFactory;
    protected $fillable = ['subject','body','scheduled_at','sent_at'];
    protected $casts = ['scheduled_at'=>'datetime','sent_at'=>'datetime'];

    public function recipients(): BelongsToMany {
        return $this->belongsToMany(User::class, 'newsletter_sends')
            ->withPivot('sent_at');
    }
}
