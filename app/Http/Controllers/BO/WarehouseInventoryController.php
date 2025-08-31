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
    public function show(Request $request, Warehouse $warehouse)
    {
        $this->authorize('view', $warehouse);
        
        $query = WarehouseInventory::with(['stockItem'])
            ->where('warehouse_id', $warehouse->id);
        
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('stockItem', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Apply low stock filter if requested
        if ($request->has('low_stock') && $request->low_stock) {
            $query->whereNotNull('min_qty')
                  ->whereRaw('qty_on_hand <= min_qty');
        }
        
        $inventory = $query->paginate(15);
        
        // Get recent movements for this warehouse
        $recentMovements = StockMovement::with(['stockItem', 'user'])
            ->where('warehouse_id', $warehouse->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('bo.warehouses.inventory', compact('warehouse', 'inventory', 'recentMovements'));
    }
}
