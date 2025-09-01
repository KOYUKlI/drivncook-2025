<?php

namespace Database\Seeders;

use App\Models\Franchisee;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReplenishmentSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = Warehouse::pluck('id','code');
        $franchisees = Franchisee::all();
        $items = StockItem::where('is_active', true)->get();
        if ($franchisees->isEmpty() || $items->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        $poCount = random_int(6, 9);
        for ($i = 0; $i < $poCount; $i++) {
            $fr = $franchisees[$i % $franchisees->count()];
            $whId = $warehouses->values()[$i % $warehouses->count()];
            $status = collect(['Draft','Approved','Picked','Shipped','Delivered','Closed'])->random();
            $reference = PurchaseOrder::nextReference();
            $po = PurchaseOrder::firstOrCreate(
                ['reference' => $reference],
                [
                    'id' => (string) Str::ulid(),
                    'warehouse_id' => $whId,
                    'franchisee_id' => $fr->id,
                    'status' => $status,
                    'kind' => 'Replenishment',
                    'created_at' => now('UTC')->subDays(random_int(1, 45)),
                ]
            );


            // Lines: central majority
            $linesCount = random_int(3, 7);
            $chosen = $items->random($linesCount);
            $centralCount = 0; $totalCents = 0; $centralCents = 0;
            foreach ($chosen as $item) {
                $qty = random_int(1, 20);
                $price = $item->price_cents;
                $pol = PurchaseOrderLine::firstOrCreate(
                    [
                        'purchase_order_id' => $po->id,
                        'stock_item_id' => $item->id,
                    ],
                    [
                        'id' => (string) Str::ulid(),
                        'qty' => $qty,
                        'unit_price_cents' => $price,
                    ]
                );
                $lineTotal = $qty * $price;
                $totalCents += $lineTotal;
                if ($item->is_central) {
                    $centralCount++;
                    $centralCents += $lineTotal;
                }
            }
            $ratio = $totalCents > 0 ? round(($centralCents / $totalCents) * 100, 2) : 0.0;
            $po->forceFill(['corp_ratio_cached' => $ratio])->save();

            // On shipped: decrement inventory through stock_movements withdrawals
            if (in_array($po->status, ['Shipped','Delivered','Closed'])) {
                $lines = PurchaseOrderLine::where('purchase_order_id', $po->id)->get();
                foreach ($lines as $line) {
                    $qty = min($line->qty, 10 + (int) floor($line->qty / 2));
                    $inv = WarehouseInventory::firstOrCreate(
                        ['warehouse_id' => $po->warehouse_id, 'stock_item_id' => $line->stock_item_id],
                        ['id' => (string) Str::ulid(), 'qty_on_hand' => 0]
                    );
                    // Prevent negative stock
                    $qtyToWithdraw = min($inv->qty_on_hand, $qty);
                    if ($qtyToWithdraw > 0) {
                        DB::transaction(function () use ($po, $line, $qtyToWithdraw, $inv) {
                            $inv->decrement('qty_on_hand', $qtyToWithdraw);
                            StockMovement::create([
                                'id' => (string) Str::ulid(),
                                'warehouse_id' => $po->warehouse_id,
                                'stock_item_id' => $line->stock_item_id,
                                'type' => StockMovement::TYPE_WITHDRAWAL,
                                'quantity' => $qtyToWithdraw,
                                'reason' => 'Replenishment shipment',
                                'ref_type' => 'REPLENISHMENT_ORDER',
                                'ref_id' => $po->id,
                                'user_id' => 1,
                            ]);
                        });
                    }
                }
            }
        }
    }
}
