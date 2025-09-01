<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WarehouseInventoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Warehouse::class);

        // If a specific warehouse is requested, show detailed inventory list; otherwise show summary index
        $warehouseId = $request->input('warehouse_id');

        if ($warehouseId) {
            $warehouse = Warehouse::findOrFail($warehouseId);

            $includeOut = $request->boolean('include_out_of_stock');

            // Stock items list respects the include_out_of_stock filter
            $stockItems = StockItem::whereIn('id', function ($q) use ($warehouse, $includeOut) {
                    $q->select('stock_item_id')
                      ->from((new WarehouseInventory())->getTable())
                      ->where('warehouse_id', $warehouse->id);
                    if (!$includeOut) {
                        $q->where('qty_on_hand', '>', 0);
                    }
                })
                ->orderBy('name')
                ->get(['id','name']);

            $inventoryQuery = WarehouseInventory::with(['warehouse','stockItem'])
                ->where('warehouse_id', $warehouse->id);
            if (!$includeOut) {
                $inventoryQuery->where('qty_on_hand', '>', 0);
            }

            if ($request->boolean('low_stock')) {
                $inventoryQuery->whereNotNull('min_qty')->whereRaw('qty_on_hand <= min_qty');
            }
            if ($request->filled('stock_item_id')) {
                $inventoryQuery->where('stock_item_id', $request->string('stock_item_id'));
            }

            $inventoryItems = $inventoryQuery->paginate(15)->withQueryString();

            // Also pass all warehouses for the filter dropdown
            $warehouses = Warehouse::orderBy('name')->get(['id','name']);

            return view('bo.warehouses.inventory', compact('warehouse', 'stockItems', 'inventoryItems', 'warehouses'));
        }

        $warehouses = Warehouse::withCount(['inventory as items_count' => function($query) {
                $query->where('qty_on_hand', '>', 0);
            }])
            ->withCount(['inventory as low_stock_count' => function($query) {
                $query->whereRaw('qty_on_hand <= min_qty')
                     ->whereNotNull('min_qty');
            }])
            ->orderBy('name')
            ->get();

        return view('bo.warehouses.inventory_index', compact('warehouses'));
    }
    public function show(Request $request, Warehouse $warehouse)
    {
        $this->authorize('view', $warehouse);

        $includeOut = $request->boolean('include_out_of_stock');

        // Stock items list respects the include_out_of_stock filter
        $stockItems = StockItem::whereIn('id', function ($q) use ($warehouse, $includeOut) {
                $q->select('stock_item_id')
                  ->from((new WarehouseInventory())->getTable())
                  ->where('warehouse_id', $warehouse->id);
                if (!$includeOut) {
                    $q->where('qty_on_hand', '>', 0);
                }
            })
            ->orderBy('name')
            ->get(['id','name']);

        $query = WarehouseInventory::with(['warehouse','stockItem'])
            ->where('warehouse_id', $warehouse->id);
        if (!$includeOut) {
            $query->where('qty_on_hand', '>', 0);
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->whereHas('stockItem', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('low_stock')) {
            $query->whereNotNull('min_qty')->whereRaw('qty_on_hand <= min_qty');
        }
        if ($request->filled('stock_item_id')) {
            $query->where('stock_item_id', $request->string('stock_item_id'));
        }

        $inventoryItems = $query->paginate(15)->withQueryString();

        // For filters
        $warehouses = Warehouse::orderBy('name')->get(['id','name']);

        return view('bo.warehouses.inventory', compact('warehouse', 'stockItems', 'inventoryItems', 'warehouses'));
    }
}
