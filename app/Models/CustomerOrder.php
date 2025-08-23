<?php

namespace App\Models;

use App\Models\Concerns\HasUlidRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CustomerOrder extends Model
{
    use HasFactory, HasUlidRouteKey;

    protected $fillable = ['truck_id','total_price','status','ordered_at','ulid'];

    public function truck(): BelongsTo {
        return $this->belongsTo(Truck::class);
    }

    // Loyalty feature removed in Mission 1 scope
    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class, 'customer_order_id');
    }

    public function recomputePaymentStatus(): void
    {
    // Always fetch fresh statuses to avoid stale cached relations during observers
    $payments = $this->payments()->select('status')->get();
        if ($payments->isEmpty()) {
            $this->payment_status = 'pending';
        } else {
            $captured = $payments->where('status','captured');
            $failed   = $payments->where('status','failed');
            $refunded = $payments->where('status','refunded');
            // All refunded -> refunded
            if ($refunded->count() === $payments->count()) {
                $this->payment_status = 'refunded';
            }
            // Any captured (and not fully refunded) -> paid (covers partial refunds)
            elseif ($captured->isNotEmpty()) {
                $this->payment_status = 'paid';
            }
            // All failed -> failed
            elseif ($failed->count() === $payments->count() && $failed->count() > 0) {
                $this->payment_status = 'failed';
            }
            else {
                $this->payment_status = 'pending';
            }
        }
        $this->save();
    }
}
