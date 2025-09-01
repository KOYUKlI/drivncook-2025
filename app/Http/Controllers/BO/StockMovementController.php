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
use Illuminate\Validation\Rule;

class StockMovementController extends Controller
{
    public function create()
    {
        $this->authorize('create', StockMovement::class);
        
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $stockItems = StockItem::where('is_active', true)->orderBy('name')->get();
        // Map inventories by warehouse for client-side filtering in the form
        $warehouseInventories = WarehouseInventory::with(['stockItem:id,name,unit'])
            ->select('warehouse_id', 'stock_item_id', 'qty_on_hand')
            ->get()
            ->groupBy('warehouse_id')
            ->map(function ($group) {
                return $group->map(function ($inv) {
                    return [
                        'id' => $inv->stock_item_id,
                        'name' => optional($inv->stockItem)->name ?? '',
                        'unit' => optional($inv->stockItem)->unit ?? '',
                        'qty_on_hand' => (int) ($inv->qty_on_hand ?? 0),
                    ];
                })->values();
            });

        return view('bo.stock_movements.create', compact('warehouses', 'stockItems', 'warehouseInventories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', StockMovement::class);
        
        // Validate common fields
        $data = $request->validate([
            'type' => ['required', Rule::in(StockMovement::getTypes())],
            'warehouse_id' => 'required|string|exists:warehouses,id',
            'stock_item_id' => 'required|string|exists:stock_items,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:1000',
        ]);
        
        // Transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            switch ($data['type']) {
                case StockMovement::TYPE_RECEIPT:
                    $this->processReceipt($data);
                    $message = __('ui.inventory.flash.receipt_success');
                    break;
                    
                case StockMovement::TYPE_WITHDRAWAL:
                    $this->processWithdrawal($data);
                    $message = __('ui.inventory.flash.withdrawal_success');
                    break;
                    
                case StockMovement::TYPE_ADJUSTMENT:
                    $request->validate([
                        'adjustment_type' => ['required', Rule::in(['increase', 'decrease'])],
                    ]);
                    
                    $data['adjustment_type'] = $request->adjustment_type;
                    $this->processAdjustment($data);
                    $message = __('ui.inventory.flash.adjustment_success');
                    break;
                    
                case StockMovement::TYPE_TRANSFER_OUT:
                    $request->validate([
                        'destination_warehouse_id' => 'required|string|exists:warehouses,id|different:warehouse_id',
                    ]);
                    
                    $data['destination_warehouse_id'] = $request->destination_warehouse_id;
                    $this->processTransfer($data);
                    $message = __('ui.inventory.flash.transfer_success');
                    break;
                    
                default:
                    throw new \Exception("Invalid movement type.");
            }
            
            DB::commit();
            return redirect()->route('bo.warehouses.inventory', $data['warehouse_id'])->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    private function processReceipt(array $data)
    {
        // Create or update inventory record
        $inventory = $this->findOrCreateInventory($data['warehouse_id'], $data['stock_item_id']);
        
        // Update quantity
        $inventory->qty_on_hand += $data['quantity'];
        $inventory->save();
        
        // Create movement record
        $this->createMovement($data, StockMovement::TYPE_RECEIPT);
    }
    
    private function processWithdrawal(array $data)
    {
        // Get inventory record
        $inventory = WarehouseInventory::where('warehouse_id', $data['warehouse_id'])
            ->where('stock_item_id', $data['stock_item_id'])
            ->first();
            
        // Check if there's enough stock
        if (!$inventory || $inventory->qty_on_hand < $data['quantity']) {
            throw new \Exception(__('ui.inventory.errors.insufficient_stock'));
        }
        
        // Update quantity
        $inventory->qty_on_hand -= $data['quantity'];
        $inventory->save();
        
        // Create movement record
        $this->createMovement($data, StockMovement::TYPE_WITHDRAWAL);
    }
    
    private function processAdjustment(array $data)
    {
        // Create or update inventory record
        $inventory = $this->findOrCreateInventory($data['warehouse_id'], $data['stock_item_id']);
        
        // Determine if it's an increase or decrease
        if ($data['adjustment_type'] === 'increase') {
            $inventory->qty_on_hand += $data['quantity'];
        } else {
            // Check if there's enough stock for decrease
            if ($inventory->qty_on_hand < $data['quantity']) {
                throw new \Exception(__('ui.inventory.errors.insufficient_stock'));
            }
            $inventory->qty_on_hand -= $data['quantity'];
        }
        
        $inventory->save();
        
        // Create movement record
        $this->createMovement($data, StockMovement::TYPE_ADJUSTMENT);
    }
    
    private function processTransfer(array $data)
    {
        // Get source inventory record
        $sourceInventory = WarehouseInventory::where('warehouse_id', $data['warehouse_id'])
            ->where('stock_item_id', $data['stock_item_id'])
            ->first();
            
        // Check if there's enough stock at source
        if (!$sourceInventory || $sourceInventory->qty_on_hand < $data['quantity']) {
            throw new \Exception(__('ui.inventory.errors.insufficient_stock'));
        }
        
        // Get or create destination inventory record
        $destInventory = $this->findOrCreateInventory($data['destination_warehouse_id'], $data['stock_item_id']);
        
        // Update quantities
        $sourceInventory->qty_on_hand -= $data['quantity'];
        $sourceInventory->save();
        
        $destInventory->qty_on_hand += $data['quantity'];
        $destInventory->save();
        
        // Create movement records for both source and destination
        // 1) Create transfer OUT first, without relation (to satisfy FK)
        $transferOut = $this->createMovement([
            'warehouse_id' => $data['warehouse_id'],
            'stock_item_id' => $data['stock_item_id'],
            'quantity' => $data['quantity'],
            'reason' => $data['reason'] ?? __('ui.inventory.transfer_to', ['warehouse' => Warehouse::find($data['destination_warehouse_id'])->name]),
        ], StockMovement::TYPE_TRANSFER_OUT);

        // 2) Create transfer IN referencing the OUT movement
        $transferIn = $this->createMovement([
            'warehouse_id' => $data['destination_warehouse_id'],
            'stock_item_id' => $data['stock_item_id'],
            'quantity' => $data['quantity'],
            'reason' => $data['reason'] ?? __('ui.inventory.transfer_from', ['warehouse' => Warehouse::find($data['warehouse_id'])->name]),
            'related_movement_id' => $transferOut->id,
        ], StockMovement::TYPE_TRANSFER_IN);

        // 3) Backfill the OUT movement relation to the IN
        $transferOut->related_movement_id = $transferIn->id;
        $transferOut->save();
    }
    
    private function findOrCreateInventory(string $warehouseId, string $stockItemId): WarehouseInventory
    {
        $inventory = WarehouseInventory::where('warehouse_id', $warehouseId)
            ->where('stock_item_id', $stockItemId)
            ->first();
            
        if (!$inventory) {
            $inventory = new WarehouseInventory([
                'id' => (string) Str::ulid(),
                'warehouse_id' => $warehouseId,
                'stock_item_id' => $stockItemId,
                'qty_on_hand' => 0,
            ]);
        }
        
        return $inventory;
    }
    
    private function createMovement(array $data, string $type): StockMovement
    {
        $movement = new StockMovement([
            'id' => $data['id'] ?? (string) Str::ulid(),
            'warehouse_id' => $data['warehouse_id'],
            'stock_item_id' => $data['stock_item_id'],
            'type' => $type,
            'quantity' => $data['quantity'],
            'reason' => $data['reason'] ?? null,
            'ref_type' => $data['ref_type'] ?? null,
            'ref_id' => $data['ref_id'] ?? null,
            'related_movement_id' => $data['related_movement_id'] ?? null,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);
        
        $movement->save();
        
        return $movement;
    }
}
