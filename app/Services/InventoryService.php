<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use App\Models\InventoryMovement;
use App\Models\InventoryLot;
use App\Models\StockOrder;
use App\Models\StockOrderItem;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    // Apply inbound movements when a stock order is completed to a warehouse
    public function receiveStockOrder(StockOrder $order): void
    {
        if (!$order->warehouse_id) {
            return; // deliveries to suppliers do not affect our inventory
        }
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $inv = Inventory::firstOrCreate([
                    'warehouse_id' => $order->warehouse_id,
                    'supply_id' => $item->supply_id,
                ], [ 'on_hand' => 0 ]);
                $inv->increment('on_hand', $item->quantity);
                InventoryMovement::create([
                    'inventory_id' => $inv->id,
                    'type' => 'in',
                    'qty' => $item->quantity,
                    'reason' => 'purchase',
                    'ref_table' => 'stock_orders',
                    'ref_id' => $order->id,
                    'created_at' => now(),
                ]);
            }
        });
    }

    public function adjust(int $inventoryId, float $qtyDiff, string $reason, ?string $note = null): void
    {
        DB::transaction(function () use ($inventoryId, $qtyDiff, $reason, $note) {
            $inv = Inventory::lockForUpdate()->findOrFail($inventoryId);
            $inv->on_hand = $inv->on_hand + $qtyDiff;
            $inv->save();

            InventoryAdjustment::create([
                'inventory_id' => $inv->id,
                'qty_diff' => $qtyDiff,
                'reason' => $reason,
                'note' => $note,
                'created_at' => now(),
            ]);
            InventoryMovement::create([
                'inventory_id' => $inv->id,
                'type' => $qtyDiff >= 0 ? 'in' : 'out',
                'qty' => abs($qtyDiff),
                'reason' => 'adjust',
                'ref_table' => 'inventory_adjustments',
                'ref_id' => null,
                'created_at' => now(),
            ]);
        });
    }

    public function transfer(int $fromInventoryId, int $toInventoryId, float $qty): void
    {
        if ($qty <= 0) return;
        DB::transaction(function () use ($fromInventoryId, $toInventoryId, $qty) {
            $from = Inventory::lockForUpdate()->findOrFail($fromInventoryId);
            $to = Inventory::lockForUpdate()->findOrFail($toInventoryId);
            if ($from->supply_id !== $to->supply_id) {
                abort(422, 'Supply mismatch for transfer');
            }
            if ($from->on_hand < $qty) {
                abort(422, 'Insufficient quantity to transfer');
            }
            $from->decrement('on_hand', $qty);
            $to->increment('on_hand', $qty);

            InventoryMovement::insert([
                [
                    'inventory_id' => $from->id,
                    'type' => 'out',
                    'qty' => $qty,
                    'reason' => 'transfer',
                    'ref_table' => 'inventory',
                    'ref_id' => $to->id,
                    'created_at' => now(),
                ],
                [
                    'inventory_id' => $to->id,
                    'type' => 'in',
                    'qty' => $qty,
                    'reason' => 'transfer',
                    'ref_table' => 'inventory',
                    'ref_id' => $from->id,
                    'created_at' => now(),
                ],
            ]);
        });
    }

    /**
     * Decrement inventory for a prepared/confirmed customer order using FIFO lots where available.
     * Each OrderItem: dish -> ingredients (not yet modeled fully, placeholder for future integration).
     * For now expects precomputed required supplies array: [inventory_id => qtyNeeded].
     */
    public function consume(array $requirements): void
    {
        DB::transaction(function () use ($requirements) {
            foreach ($requirements as $inventoryId => $qtyNeeded) {
                if ($qtyNeeded <= 0) continue;
                $inv = Inventory::lockForUpdate()->findOrFail($inventoryId);
                if ($inv->on_hand < $qtyNeeded) {
                    abort(422, 'Insufficient stock for inventory '.$inventoryId);
                }

                // FIFO lots
                $remaining = $qtyNeeded;
                $lots = InventoryLot::where('inventory_id', $inventoryId)
                    ->orderBy('expires_at')
                    ->orderBy('id')
                    ->lockForUpdate()->get();
                foreach ($lots as $lot) {
                    if ($remaining <= 0) break;
                    $take = min($lot->qty, $remaining);
                    if ($take > 0) {
                        $lot->qty -= $take;
                        $lot->save();
                        InventoryMovement::create([
                            'inventory_id' => $inv->id,
                            'type' => 'out',
                            'qty' => $take,
                            'reason' => 'sale',
                            'ref_table' => 'inventory_lots',
                            'ref_id' => $lot->id,
                            'created_at' => now(),
                        ]);
                        $remaining -= $take;
                    }
                }
                // Any remaining taken from bulk (no lot)
                if ($remaining > 0) {
                    InventoryMovement::create([
                        'inventory_id' => $inv->id,
                        'type' => 'out',
                        'qty' => $remaining,
                        'reason' => 'sale',
                        'ref_table' => null,
                        'ref_id' => null,
                        'created_at' => now(),
                    ]);
                }
                $inv->decrement('on_hand', $qtyNeeded);
            }
        });
    }

}
