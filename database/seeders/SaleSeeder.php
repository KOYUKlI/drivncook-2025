<?php

namespace Database\Seeders;

use App\Models\Franchisee;
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\StockItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $franchisees = Franchisee::all();
        $items = StockItem::where('is_active', true)->get();
        if ($franchisees->isEmpty() || $items->isEmpty()) {
            return;
        }

        foreach ($franchisees as $fr) {
            // For the last 60 days, create realistic distribution
            for ($d = 60; $d >= 1; $d--) {
                $date = now('UTC')->subDays($d)->format('Y-m-d');
                // Some zero days
                if (crc32($fr->id.$date) % 7 === 0) {
                    continue;
                }
                $sale = Sale::firstOrCreate(
                    ['franchisee_id' => $fr->id, 'sale_date' => $date],
                    ['id' => (string) Str::ulid(), 'total_cents' => 0]
                );
                $linesCount = random_int(1, 4);
                $selected = $items->random($linesCount);
                $total = 0;
                foreach ($selected as $item) {
                    $qty = random_int(1, 8);
                    $price = random_int(max(100, (int)($item->price_cents*0.8)), (int)($item->price_cents*1.2));
                    SaleLine::updateOrCreate(
                        ['sale_id' => $sale->id, 'stock_item_id' => $item->id],
                        [
                            'id' => (string) Str::ulid(),
                            'qty' => $qty,
                            'unit_price_cents' => $price,
                        ]
                    );
                    $total += $qty * $price;
                }
                $sale->forceFill(['total_cents' => $total])->save();
            }
        }
    }
}
