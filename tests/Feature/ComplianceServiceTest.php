<?php

use App\Models\{Franchise, Truck, Warehouse, Supplier, StockOrder, StockOrderItem, ComplianceKpi, Supply};
use App\Services\ComplianceService;
use Illuminate\Support\Facades\App;

it('computes compliance KPI external turnover from purchase mix view', function () {
    $franchise = Franchise::factory()->create();
    $truck = Truck::factory()->create(['franchise_id' => $franchise->id]);
    $warehouse = Warehouse::factory()->create(['franchise_id' => $franchise->id]);
    $supplier = Supplier::create(['name' => 'Supp X']);
    $month = now()->format('Y-m');

    $supply = Supply::create(['name' => 'MixTest','unit' => 'u','cost' => 1]);

    $o1 = StockOrder::create([
        'truck_id' => $truck->id,
        'warehouse_id' => $warehouse->id,
        'supplier_id' => null,
        'status' => 'completed',
        'created_at' => now()->startOfMonth()->addDay(),
        'updated_at' => now(),
    ]);
    StockOrderItem::create([
        'stock_order_id' => $o1->id,
        'supply_id' => $supply->id,
        'quantity' => 2,
        'unit_price' => 10,
    ]);

    $o2 = StockOrder::create([
        'truck_id' => $truck->id,
        'warehouse_id' => null,
        'supplier_id' => $supplier->id,
        'status' => 'completed',
        'created_at' => now()->startOfMonth()->addDays(2),
        'updated_at' => now(),
    ]);
    StockOrderItem::create([
        'stock_order_id' => $o2->id,
        'supply_id' => $supply->id,
        'quantity' => 3,
        'unit_price' => 20,
    ]);

    /** @var ComplianceService $svc */
    $svc = App::make(ComplianceService::class);
    $svc->computeMonth($month);

    $kpi = ComplianceKpi::first();
    expect($kpi)->not()->toBeNull();
    expect((float)$kpi->external_turnover)->toBe(60.0);
});
