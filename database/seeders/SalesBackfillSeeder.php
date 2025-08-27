<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesBackfillSeeder extends Seeder
{
    public function run(): void
    {
        $fid = DB::table('franchisees')->value('id');
        $items = DB::table('stock_items')->pluck('id', 'sku');

        for ($d = 0; $d < 60; $d++) {
            $saleId = (string) Str::ulid();
            $date = now()->subDays($d)->toDateString();
            DB::table('sales')->insert([
                'id' => $saleId, 'franchisee_id' => $fid,
                'sale_date' => $date, 'total_cents' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            $total = 0;
            foreach (['BUN-001', 'MEAT-001', 'SAUCE-001'] as $sku) {
                $qty = rand(1, 4);
                $price = (int) DB::table('stock_items')->where('id', $items[$sku])->value('price_cents') ?: 500;
                $total += $qty * $price;
                DB::table('sale_lines')->insert([
                    'id' => (string) Str::ulid(), 'sale_id' => $saleId,
                    'stock_item_id' => $items[$sku] ?? null, 'qty' => $qty,
                    'unit_price_cents' => $price, 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            DB::table('sales')->where('id', $saleId)->update(['total_cents' => $total]);
        }
    }
}
