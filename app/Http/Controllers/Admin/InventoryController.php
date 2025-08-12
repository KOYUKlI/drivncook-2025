<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\InventoryAdjustRequest;
use App\Http\Requests\Admin\InventoryMoveRequest;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $warehouseId = $request->integer('warehouse_id');
        $supplyId = $request->integer('supply_id');
        $query = Inventory::query()->with(['warehouse','supply']);
        if ($warehouseId) { $query->where('warehouse_id', $warehouseId); }
        if ($supplyId) { $query->where('supply_id', $supplyId); }
        $items = $query->orderBy('warehouse_id')->orderBy('supply_id')->paginate(25)->withQueryString();
        $warehouses = Warehouse::orderBy('name')->get();
        $supplies = Supply::orderBy('name')->get();
        return view('admin.inventory.index', compact('items', 'warehouses', 'supplies', 'warehouseId', 'supplyId'));
    }

    public function show(Inventory $inventory): View
    {
        $inventory->load(['warehouse','supply','lots' => function($q){ $q->orderBy('expires_at'); }]);
        $movements = $inventory->movements()->orderByDesc('created_at')->limit(20)->get();
        // Potential destinations for transfer (same supply, other warehouses)
        $destinations = Inventory::with('warehouse')
            ->where('supply_id', $inventory->supply_id)
            ->where('id', '!=', $inventory->id)
            ->orderBy('warehouse_id')
            ->get();
        return view('admin.inventory.show', compact('inventory','movements','destinations'));
    }

    public function adjust(InventoryAdjustRequest $request, InventoryService $service): RedirectResponse
    {
        $data = $request->validated();
        $service->adjust($data['inventory_id'], (float)$data['qty_diff'], $data['reason'], $data['note'] ?? null);
        return back()->with('success', 'Inventory adjusted.');
    }

    public function move(InventoryMoveRequest $request, InventoryService $service): RedirectResponse
    {
        $data = $request->validated();
        $service->transfer($data['from_inventory_id'], $data['to_inventory_id'], (float)$data['qty']);
        return back()->with('success', 'Inventory transferred.');
    }
}
