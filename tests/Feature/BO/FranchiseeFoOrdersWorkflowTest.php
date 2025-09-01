<?php

use App\Models\Franchisee;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'warehouse']);
    Role::firstOrCreate(['name' => 'franchisee']);

    // Users
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->whUser = User::factory()->create();
    $this->whUser->assignRole('warehouse');

    // Franchisee
    $this->fr = Franchisee::factory()->create();

    // Warehouse
    $this->warehouse = Warehouse::factory()->create();

    // Stock items
    $this->itemA = StockItem::factory()->create();
    $this->itemB = StockItem::factory()->create();

    // Order (Submitted)
    $this->order = PurchaseOrder::factory()->create([
        'kind' => 'franchisee_po',
        'status' => 'Submitted',
        'franchisee_id' => $this->fr->id,
    ]);

    // Lines
    $this->lineA = PurchaseOrderLine::factory()->create([
        'purchase_order_id' => $this->order->id,
        'stock_item_id' => $this->itemA->id,
        'qty' => 10,
        'qty_picked' => 0,
        'qty_shipped' => 0,
        'qty_delivered' => 0,
    ]);
    $this->lineB = PurchaseOrderLine::factory()->create([
        'purchase_order_id' => $this->order->id,
        'stock_item_id' => $this->itemB->id,
        'qty' => 5,
        'qty_picked' => 0,
        'qty_shipped' => 0,
        'qty_delivered' => 0,
    ]);
});

it('approves a submitted order and assigns a warehouse', function () {
    $res = $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.approve', $this->order), [
            'warehouse_id' => $this->warehouse->id,
        ]);

    $res->assertStatus(302);
    $res->assertSessionHas('success');

    $this->order->refresh();
    expect($this->order->status)->toBe('Approved');
    expect($this->order->warehouse_id)->toBe($this->warehouse->id);
    expect($this->order->approved_at)->not->toBeNull();
});

it('picks quantities for an approved order', function () {
    // First approve
    $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.approve', $this->order), ['warehouse_id' => $this->warehouse->id]);

    $res = $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.pick', $this->order), [
            'lines' => [
                $this->lineA->id => 8,
                $this->lineB->id => 2,
            ],
        ]);

    $res->assertStatus(302);
    $res->assertSessionHas('success');

    $this->lineA->refresh();
    $this->lineB->refresh();
    $this->order->refresh();

    expect($this->order->status)->toBe('Picked');
    expect($this->lineA->qty_picked)->toBe(8);
    expect($this->lineB->qty_picked)->toBe(2);
});

it('ships within available quantities, decreases inventory and creates stock movements', function () {
    // Approve
    $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.approve', $this->order), ['warehouse_id' => $this->warehouse->id]);

    // Seed inventory
    WarehouseInventory::create([
        'id' => (string) \Illuminate\Support\Str::ulid(),
        'warehouse_id' => $this->warehouse->id,
        'stock_item_id' => $this->itemA->id,
        'qty_on_hand' => 20,
    ]);
    WarehouseInventory::create([
        'id' => (string) \Illuminate\Support\Str::ulid(),
        'warehouse_id' => $this->warehouse->id,
        'stock_item_id' => $this->itemB->id,
        'qty_on_hand' => 10,
    ]);

    $res = $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.ship', $this->order), [
            'lines' => [
                $this->lineA->id => 7, // <= qty 10
                $this->lineB->id => 5, // == qty 5
            ],
        ]);

    $res->assertStatus(302);
    $res->assertSessionHas('success');

    // Assert order & lines
    $this->order->refresh();
    $this->lineA->refresh();
    $this->lineB->refresh();
    expect($this->order->status)->toBe('Shipped');
    expect($this->order->shipped_at)->not->toBeNull();
    expect($this->lineA->qty_shipped)->toBe(7);
    expect($this->lineB->qty_shipped)->toBe(5);

    // Inventory decreased
    $invA = WarehouseInventory::where('warehouse_id', $this->warehouse->id)->where('stock_item_id', $this->itemA->id)->firstOrFail();
    $invB = WarehouseInventory::where('warehouse_id', $this->warehouse->id)->where('stock_item_id', $this->itemB->id)->firstOrFail();
    expect($invA->qty_on_hand)->toBe(13);
    expect($invB->qty_on_hand)->toBe(5);

    // Stock movements created with correct ref
    $movA = StockMovement::where('warehouse_id', $this->warehouse->id)
        ->where('stock_item_id', $this->itemA->id)
        ->where('type', StockMovement::TYPE_WITHDRAWAL)
        ->where('ref_type', 'FRANCHISEE_PO')
        ->where('ref_id', $this->order->id)
        ->first();
    $movB = StockMovement::where('warehouse_id', $this->warehouse->id)
        ->where('stock_item_id', $this->itemB->id)
        ->where('type', StockMovement::TYPE_WITHDRAWAL)
        ->where('ref_type', 'FRANCHISEE_PO')
        ->where('ref_id', $this->order->id)
        ->first();
    expect($movA)->not->toBeNull();
    expect($movB)->not->toBeNull();
});

it('prevents shipping above ordered quantities (409) and with insufficient stock (422)', function () {
    // Approve
    $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.approve', $this->order), ['warehouse_id' => $this->warehouse->id]);

    // Inventory only for itemA, and not enough for itemB
    WarehouseInventory::create([
        'id' => (string) \Illuminate\Support\Str::ulid(),
        'warehouse_id' => $this->warehouse->id,
        'stock_item_id' => $this->itemA->id,
        'qty_on_hand' => 5,
    ]);
    WarehouseInventory::create([
        'id' => (string) \Illuminate\Support\Str::ulid(),
        'warehouse_id' => $this->warehouse->id,
        'stock_item_id' => $this->itemB->id,
        'qty_on_hand' => 1,
    ]);

    // Exceed ordered for A (qty 10 ordered, try 11) -> 409
    $tooMuch = $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.ship', $this->order), [
            'lines' => [ $this->lineA->id => 11 ],
        ]);
    $tooMuch->assertStatus(409);

    // Insufficient stock for B (ordered 5, try 2 but only 1 in stock) -> 422
    $insufficient = $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.ship', $this->order), [
            'lines' => [ $this->lineB->id => 2 ],
        ]);
    $insufficient->assertStatus(422);
});

it('delivers only up to shipped quantities and updates timestamps', function () {
    // Approve + seed inventory + ship some
    $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.approve', $this->order), ['warehouse_id' => $this->warehouse->id]);
    WarehouseInventory::create([
        'id' => (string) \Illuminate\Support\Str::ulid(),
        'warehouse_id' => $this->warehouse->id,
        'stock_item_id' => $this->itemA->id,
        'qty_on_hand' => 20,
    ]);
    $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.ship', $this->order), [
            'lines' => [ $this->lineA->id => 6 ],
        ]);

    $this->order->refresh();
    $this->lineA->refresh();
    expect($this->order->status)->toBe('Shipped');
    expect($this->lineA->qty_shipped)->toBe(6);

    // Try deliver above shipped -> 409
    $tooMuch = $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.deliver', $this->order), [
            'lines' => [ $this->lineA->id => 7 ],
        ]);
    $tooMuch->assertStatus(409);

    // Valid deliver
    $ok = $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.deliver', $this->order), [
            'lines' => [ $this->lineA->id => 6 ],
        ]);
    $ok->assertStatus(302);
    $ok->assertSessionHas('success');

    $this->order->refresh();
    $this->lineA->refresh();
    expect($this->order->status)->toBe('Delivered');
    expect($this->order->delivered_at)->not->toBeNull();
    expect($this->lineA->qty_delivered)->toBe(6);
});

it('cannot cancel after shipment (invalid transition 409)', function () {
    // Approve + seed inventory + ship
    $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.approve', $this->order), ['warehouse_id' => $this->warehouse->id]);
    WarehouseInventory::create([
        'id' => (string) \Illuminate\Support\Str::ulid(),
        'warehouse_id' => $this->warehouse->id,
        'stock_item_id' => $this->itemA->id,
        'qty_on_hand' => 20,
    ]);
    $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.ship', $this->order), [
            'lines' => [ $this->lineA->id => 1 ],
        ]);

    $this->order->refresh();
    expect($this->order->status)->toBe('Shipped');

    $res = $this->actingAs($this->whUser)
        ->post(route('bo.fo-orders.cancel', $this->order));
    $res->assertStatus(409);
});
