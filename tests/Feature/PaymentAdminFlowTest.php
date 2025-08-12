<?php

use App\Models\User;
use App\Models\CustomerOrder;
use App\Models\Truck;
use App\Models\Payment;
use function Pest\Laravel\{actingAs, post};

it('admin can record and capture and refund a payment', function() {
    $admin = User::factory()->create(['role'=>'admin']);
    $truck = Truck::factory()->create();
    $order = CustomerOrder::factory()->create(['truck_id'=>$truck->id,'total_price'=>100]);
    actingAs($admin);
    // store payment (cash -> auto capture)
    post(route('admin.sales.payments.store',$order), [
        'amount' => 100,
        'method' => 'cash',
        '_token' => csrf_token()
    ])->assertRedirect(route('admin.sales.show',$order));
    $payment = Payment::first();
    expect($payment->status)->toBe('captured');
    // refund
    post(route('admin.payments.refund',$payment), ['_token'=>csrf_token()])
        ->assertRedirect(route('admin.payments.show',$payment));
    $payment->refresh();
    expect($payment->status)->toBe('refunded');
});
