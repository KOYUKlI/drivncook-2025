<?php

use App\Models\{CustomerOrder, OrderItem, Truck, Franchise, Dish};

it('recomputes customer order total via triggers', function () {
    $franchise = Franchise::factory()->create();
    $truck = Truck::factory()->create(['franchise_id' => $franchise->id]);
    $order = CustomerOrder::factory()->create(['truck_id' => $truck->id, 'total_price' => 0]);
    $dish = Dish::create(['name' => 'Burger X', 'price' => 5.00]);

    $item = OrderItem::create([
        'customer_order_id' => $order->id,
        'dish_id' => $dish->id,
        'quantity' => 2,
        'price' => 5.00,
    ]);
    $order->refresh();
    expect((float)$order->total_price)->toBe(10.00);

    $item->update(['quantity' => 3]);
    $order->refresh();
    expect((float)$order->total_price)->toBe(15.00);

    $item2 = OrderItem::create([
        'customer_order_id' => $order->id,
        'dish_id' => $dish->id,
        'quantity' => 1,
        'price' => 7.00,
    ]);
    $order->refresh();
    expect((float)$order->total_price)->toBe(22.00);

    $item->delete();
    $order->refresh();
    expect((float)$order->total_price)->toBe(7.00);
});
