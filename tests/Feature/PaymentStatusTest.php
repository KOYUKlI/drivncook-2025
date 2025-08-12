<?php

use App\Models\CustomerOrder;
use App\Models\Payment;

it('updates order payment_status when payments captured', function () {
    $order = CustomerOrder::factory()->create(['total_price' => 50]);
    expect($order->payment_status)->toBe('pending');

    Payment::create([
        'customer_order_id' => $order->id,
        'amount' => 50,
        'method' => 'card',
        'status' => 'captured',
        'captured_at' => now(),
    ]);

    $order->refresh();
    expect($order->payment_status)->toBe('paid');
});

it('sets refunded when all payments refunded', function () {
    $order = CustomerOrder::factory()->create(['total_price' => 40]);
    $p = Payment::create([
        'customer_order_id' => $order->id,
        'amount' => 40,
        'method' => 'card',
        'status' => 'captured',
        'captured_at' => now(),
    ]);
    $order->refresh();
    expect($order->payment_status)->toBe('paid');

    $p->update(['status' => 'refunded','refunded_at' => now()]);
    $order->refresh();
    expect($order->payment_status)->toBe('refunded');
});
