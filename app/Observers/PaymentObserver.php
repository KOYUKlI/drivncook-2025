<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $payment->order?->recomputePaymentStatus();
    }
    public function updated(Payment $payment): void
    {
        $payment->order?->recomputePaymentStatus();
    }
    public function deleted(Payment $payment): void
    {
        $payment->order?->recomputePaymentStatus();
    }
}
