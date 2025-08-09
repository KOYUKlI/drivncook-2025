<?php

namespace App\Services;

use App\Models\Inventory;
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
            }
        });
    }
}
