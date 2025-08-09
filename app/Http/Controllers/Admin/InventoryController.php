<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $inventory->load(['warehouse','supply']);
        $movements = $inventory->movements()->orderByDesc('created_at')->limit(20)->get();
        return view('admin.inventory.show', compact('inventory','movements'));
    }
}
