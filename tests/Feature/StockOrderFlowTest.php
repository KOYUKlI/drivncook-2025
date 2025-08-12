<?php

use App\Models\User;
use App\Models\Franchise;
use App\Models\Truck;
use App\Models\Warehouse;
use App\Models\Supply;
use App\Models\StockOrder;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\get;

it('franchise can create stock order to warehouse, add item and complete', function () {
    $franchise = Franchise::factory()->create();
    $user = User::factory()->create(['role' => 'franchise', 'franchise_id' => $franchise->id, 'password' => Hash::make('password')]);
    $truck = Truck::factory()->create(['franchise_id' => $franchise->id]);
    $warehouse = Warehouse::factory()->create(['franchise_id' => $franchise->id]);
    $supply = Supply::create(['name' => 'Cheese', 'unit' => 'kg', 'cost' => 4.2]);

    actingAs($user);
    // create order
    post(route('franchise.stockorders.store'), [
        'truck_id' => $truck->id,
        'warehouse_id' => $warehouse->id,
    ])->assertRedirect();
    $order = StockOrder::latest('id')->first();
    // add item
    post(route('franchise.stockorders.items.store', $order), [
        'supply_id' => $supply->id,
        'quantity' => 3,
    ])->assertRedirect();
    // complete
    post(route('franchise.stockorders.complete', $order))->assertRedirect();
});
