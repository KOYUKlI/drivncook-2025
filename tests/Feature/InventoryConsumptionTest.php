<?php

use App\Models\{Warehouse, Supply, Inventory, InventoryLot};
use App\Services\InventoryService;
use Illuminate\Support\Facades\App;

it('consumes inventory FIFO lots then bulk remainder', function () {
    $warehouse = Warehouse::factory()->create();
    $supply = Supply::create(['name' => 'Tomato', 'unit' => 'kg', 'cost' => 1.5]);
    $inv = Inventory::create(['warehouse_id' => $warehouse->id, 'supply_id' => $supply->id, 'on_hand' => 15]);
    $lot1 = InventoryLot::create(['inventory_id' => $inv->id, 'lot_code' => 'A', 'qty' => 5, 'expires_at' => now()->addDays(5)]);
    $lot2 = InventoryLot::create(['inventory_id' => $inv->id, 'lot_code' => 'B', 'qty' => 4, 'expires_at' => now()->addDays(10)]);

    /** @var InventoryService $svc */
    $svc = App::make(InventoryService::class);
    $svc->consume([$inv->id => 9]);

    $inv->refresh();
    $lot1->refresh();
    $lot2->refresh();

    expect((float)$inv->on_hand)->toBe(6.0);
    expect((float)$lot1->qty)->toBe(0.0);
    expect((float)$lot2->qty)->toBe(0.0);
});
