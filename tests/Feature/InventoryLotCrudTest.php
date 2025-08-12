<?php

use App\Models\User;
use App\Models\Inventory;
use App\Models\InventoryLot;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;

it('admin can create, update and delete an inventory lot while on_hand adjusts', function () {
    $admin = User::factory()->create(['role' => 'admin', 'password' => Hash::make('password')]);
    $inventory = Inventory::factory()->create(['on_hand' => 0]);
    actingAs($admin);
    post(route('admin.inventory.lots.store', $inventory), [
        'lot_code' => 'LOT-001',
        'qty' => 5.2,
    ])->assertRedirect(route('admin.inventory.show', $inventory));
    $inventory->refresh();
    expect($inventory->on_hand)->toBeFloat()->toBe(5.2);
    $lot = InventoryLot::where('inventory_id', $inventory->id)->first();
    put(route('admin.inventory.lots.update', [$inventory, $lot]), [
        'lot_code' => 'LOT-001',
        'qty' => 7.0,
    ])->assertRedirect(route('admin.inventory.show', $inventory));
    $inventory->refresh();
    expect($inventory->on_hand)->toBe(7.0);
    delete(route('admin.inventory.lots.destroy', [$inventory, $lot]))->assertRedirect(route('admin.inventory.show', $inventory));
    $inventory->refresh();
    expect($inventory->on_hand)->toBe(0.0);
});

it('rejects duplicate lot code per inventory', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $inventory = Inventory::factory()->create();
    actingAs($admin);
    post(route('admin.inventory.lots.store', $inventory), [
        'lot_code' => 'DUP',
        'qty' => 1,
    ])->assertRedirect();
    post(route('admin.inventory.lots.store', $inventory), [
        'lot_code' => 'DUP',
        'qty' => 1,
    ])->assertSessionHasErrors('lot_code');
});
