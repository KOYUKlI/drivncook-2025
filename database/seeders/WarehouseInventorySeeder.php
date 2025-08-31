<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseInventorySeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = DB::table('warehouses')->select('id', 'code')->get();
        $items = DB::table('stock_items')->select('id', 'sku', 'is_central')->get();

        if ($warehouses->isEmpty() || $items->isEmpty()) {
            $this->command?->warn('No warehouses or stock items found, skipping WarehouseInventorySeeder.');
            return;
        }

        foreach ($warehouses as $wh) {
            foreach ($items as $item) {
                // Rule: central items exist everywhere; local items only in Paris (PAR) and one other site
                $isLocal = ! $item->is_central;
                if ($isLocal) {
                    $allow = in_array($wh->code, ['WH-PAR', 'WH-NTR']);
                    if (! $allow) { continue; }
                }

                $min = match (true) {
                    str_starts_with($item->sku, 'MEAT') => 20,
                    str_starts_with($item->sku, 'BUN') => 30,
                    str_starts_with($item->sku, 'POT') => 25,
                    default => 10,
                };
                $max = $min * 4; // simple policy

                // qty_on_hand: vary per warehouse to showcase low-stock
                $base = $min + random_int(0, $min * 2);
                if ($wh->code === 'WH-CRL' && str_starts_with($item->sku, 'MEAT')) {
                    $base = max(0, (int) floor($min * 0.5)); // force low stock for demo
                }
                if ($wh->code === 'WH-SDN' && str_starts_with($item->sku, 'OIL')) {
                    $base = max(0, (int) floor($min * 0.4));
                }

                // Upsert by unique (warehouse_id, stock_item_id)
                $exists = DB::table('warehouse_inventories')
                    ->where('warehouse_id', $wh->id)
                    ->where('stock_item_id', $item->id)
                    ->exists();

                $payload = [
                    'qty_on_hand' => $base,
                    'min_qty' => $min,
                    'max_qty' => $max,
                    'updated_at' => now(),
                ];

                if ($exists) {
                    DB::table('warehouse_inventories')
                        ->where('warehouse_id', $wh->id)
                        ->where('stock_item_id', $item->id)
                        ->update($payload);
                } else {
                    DB::table('warehouse_inventories')->insert(array_merge($payload, [
                        'id' => (string) \Illuminate\Support\Str::ulid(),
                        'warehouse_id' => $wh->id,
                        'stock_item_id' => $item->id,
                        'created_at' => now(),
                    ]));
                }
            }
        }
    }
}
