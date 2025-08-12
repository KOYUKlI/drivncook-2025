<?php

use App\Models\User;
use App\Models\Warehouse;
use App\Models\Supply;
use App\Models\Inventory;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('allows admin to adjust inventory', function () {
    $admin = User::factory()->create(['role' => 'admin', 'password' => Hash::make('password')]);
    $warehouse = Warehouse::factory()->create();
    $supply = Supply::create(['name' => 'Flour', 'unit' => 'kg', 'cost' => 2.5]);
    $inv = Inventory::create(['warehouse_id' => $warehouse->id, 'supply_id' => $supply->id, 'on_hand' => 10]);

    actingAs($admin);
    post(route('admin.inventory.adjust'), [
        'inventory_id' => $inv->id,
        'qty_diff' => 2.5,
        'reason' => 'audit',
        'note' => 'count fix',
    ])->assertRedirect();
});

it('rejects transfer between different supplies', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $w1 = Warehouse::factory()->create();
    $w2 = Warehouse::factory()->create();
    $s1 = Supply::create(['name' => 'Sugar', 'unit' => 'kg', 'cost' => 1.1]);
    $s2 = Supply::create(['name' => 'Salt', 'unit' => 'kg', 'cost' => 0.9]);
    $i1 = Inventory::create(['warehouse_id' => $w1->id, 'supply_id' => $s1->id, 'on_hand' => 5]);
    $i2 = Inventory::create(['warehouse_id' => $w2->id, 'supply_id' => $s2->id, 'on_hand' => 5]);

    actingAs($admin);
    post(route('admin.inventory.move'), [
        'from_inventory_id' => $i1->id,
        'to_inventory_id' => $i2->id,
        'qty' => 1.0,
    ])->assertStatus(422);
});
