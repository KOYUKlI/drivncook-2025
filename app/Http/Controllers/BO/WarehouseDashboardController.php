<?php

namespace App\Http\Controllers\BO;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WarehouseDashboardController extends Controller
{
    /**
     * Display warehouse dashboard.
     */
    public function show(Request $request, string $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        // Default date filters
        $fromDate = $request->input('from_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->format('Y-m-d'));
        
        // Parse dates
        $fromCarbon = Carbon::parse($fromDate)->startOfDay();
        $toCarbon = Carbon::parse($toDate)->endOfDay();
        
        // Movement type filter
        $movementType = $request->input('movement_type', '');
        
        // Calculate KPIs
        $kpis = [
            'active_items_count' => StockItem::where('is_active', true)->count(),
            'low_stock_count' => WarehouseInventory::where('warehouse_id', $id)
                ->whereNotNull('min_qty')
                ->whereRaw('qty_on_hand <= min_qty')
                ->count(),
            'movements_7days' => StockMovement::where('warehouse_id', $id)
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
            'movements_30days' => StockMovement::where('warehouse_id', $id)
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count(),
            'po_received_30days' => PurchaseOrder::where('warehouse_id', $id)
                ->where('status', 'Received')
                ->where('updated_at', '>=', Carbon::now()->subDays(30))
                ->count(),
        ];
        
        // Get recent movements with pagination
        $movementsQuery = StockMovement::with(['stockItem', 'user'])
            ->where('warehouse_id', $id)
            ->whereBetween('created_at', [$fromCarbon, $toCarbon]);
        
        // Apply movement type filter if specified
        if ($movementType) {
            $movementsQuery->where('type', $movementType);
        }
        
        $movements = $movementsQuery->latest()->paginate(15);
        
        // For movement type filter dropdown
        $movementTypes = StockMovement::getTypes();
        
        return view('bo.warehouses.dashboard', compact(
            'warehouse', 
            'kpis', 
            'movements',
            'fromDate',
            'toDate',
            'movementType',
            'movementTypes'
        ));
    }

    /**
     * Export movements to CSV.
     */
    public function exportMovements(Request $request, string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        // Date filters
        $fromDate = $request->input('from_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->format('Y-m-d'));
        
        // Parse dates
        $fromCarbon = Carbon::parse($fromDate)->startOfDay();
        $toCarbon = Carbon::parse($toDate)->endOfDay();
        
        // Movement type filter
        $movementType = $request->input('movement_type', '');
        
        // Query movements
        $movementsQuery = StockMovement::with(['stockItem', 'user'])
            ->where('warehouse_id', $id)
            ->whereBetween('created_at', [$fromCarbon, $toCarbon]);
        
        // Apply movement type filter if specified
        if ($movementType) {
            $movementsQuery->where('type', $movementType);
        }
        
        $movements = $movementsQuery->latest()->get();
        
        // Create CSV
        $headers = [
            __('warehouse_dashboard.inventory.dashboard.export.date'),
            __('warehouse_dashboard.inventory.dashboard.export.type'),
            __('warehouse_dashboard.inventory.dashboard.export.item'),
            __('warehouse_dashboard.inventory.dashboard.export.quantity'),
            __('warehouse_dashboard.inventory.dashboard.export.user'),
            __('warehouse_dashboard.inventory.dashboard.export.reason')
        ];
        
        $callback = function() use ($movements, $headers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($file, $headers);
            
            // Add data
            foreach ($movements as $movement) {
                $row = [
                    $movement->created_at->format('Y-m-d H:i:s'),
                    __('warehouse_dashboard.inventory.movement_types.' . $movement->type),
                    $movement->stockItem->name,
                    $movement->quantity,
                    $movement->user ? $movement->user->name : 'N/A',
                    $movement->reason
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        $filename = 'warehouse_movements_' . $warehouse->name . '_' . date('Y-m-d') . '.csv';
        
        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
