<?php

use App\Models\{Franchise, Truck, Warehouse, Supplier, User};
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('rejects creation when both warehouse and supplier are provided', function () {
    $franchise = Franchise::factory()->create();
    $user = User::factory()->create(['role' => 'franchise','franchise_id' => $franchise->id]);
    $truck = Truck::factory()->create(['franchise_id' => $franchise->id]);
    $warehouse = Warehouse::factory()->create(['franchise_id' => $franchise->id]);
    $supplier = Supplier::create(['name' => 'Supp XOR']);
    actingAs($user);
    post(route('franchise.stockorders.store'), [
        'truck_id' => $truck->id,
        'warehouse_id' => $warehouse->id,
        'supplier_id' => $supplier->id,
    ])->assertSessionHasErrors(['warehouse_id','supplier_id']);
});

it('rejects creation when neither warehouse nor supplier is provided', function () {
    $franchise = Franchise::factory()->create();
    $user = User::factory()->create(['role' => 'franchise','franchise_id' => $franchise->id]);
    $truck = Truck::factory()->create(['franchise_id' => $franchise->id]);
    actingAs($user);
    post(route('franchise.stockorders.store'), [
        'truck_id' => $truck->id,
    ])->assertSessionHasErrors(['warehouse_id','supplier_id']);
});
