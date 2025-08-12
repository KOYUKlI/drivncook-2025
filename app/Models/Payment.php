<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_order_id','amount','method','provider_ref','status','captured_at','refunded_at','refund_parent_id'
    ];

    protected $casts = [
        'captured_at' => 'datetime',
        'refunded_at' => 'datetime'
    ];

    public function order(): BelongsTo { return $this->belongsTo(CustomerOrder::class, 'customer_order_id'); }
    public function refundParent(): BelongsTo { return $this->belongsTo(Payment::class, 'refund_parent_id'); }
    public function refunds(): HasMany { return $this->hasMany(Payment::class, 'refund_parent_id'); }

    public function markCaptured(): void
    {
        $this->status = 'captured';
        $this->captured_at = now();
        $this->save();
    }
    public function markFailed(): void
    {
        $this->status = 'failed';
        $this->save();
    }
    public function markRefunded(): void
    {
        $this->status = 'refunded';
        $this->refunded_at = now();
        $this->save();
    }
}
