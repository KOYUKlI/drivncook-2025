<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Central (12)
            ['sku' => 'BUN-001', 'name' => 'Pain burger classique', 'unit' => 'pack', 'price_cents' => 250, 'is_central' => true],
            ['sku' => 'BUN-GLU', 'name' => 'Pain burger sans gluten', 'unit' => 'pack', 'price_cents' => 380, 'is_central' => true],
            ['sku' => 'MEAT-001', 'name' => 'Steak 150g', 'unit' => 'pcs', 'price_cents' => 1200, 'is_central' => true],
            ['sku' => 'MEAT-VEG', 'name' => 'Steak végétal', 'unit' => 'pcs', 'price_cents' => 950, 'is_central' => true],
            ['sku' => 'CHS-CHED', 'name' => 'Cheddar', 'unit' => 'kg', 'price_cents' => 980, 'is_central' => true],
            ['sku' => 'CHS-RACL', 'name' => 'Raclette', 'unit' => 'kg', 'price_cents' => 1250, 'is_central' => true],
            ['sku' => 'SAUCE-001', 'name' => 'Sauce maison', 'unit' => 'btl', 'price_cents' => 300, 'is_central' => true],
            ['sku' => 'SAUCE-SPC', 'name' => 'Sauce épicée', 'unit' => 'btl', 'price_cents' => 320, 'is_central' => true],
            ['sku' => 'VEG-TOM', 'name' => 'Tomates', 'unit' => 'kg', 'price_cents' => 450, 'is_central' => true],
            ['sku' => 'VEG-LET', 'name' => 'Salade', 'unit' => 'kg', 'price_cents' => 350, 'is_central' => true],
            ['sku' => 'POT-FRY', 'name' => 'Frites surgelées', 'unit' => 'kg', 'price_cents' => 260, 'is_central' => true],
            ['sku' => 'OIL-FRY', 'name' => 'Huile de friture', 'unit' => 'L', 'price_cents' => 220, 'is_central' => true],
            // Local (8)
            ['sku' => 'DRINK-EXT', 'name' => 'Soda local', 'unit' => 'btl', 'price_cents' => 200, 'is_central' => false],
            ['sku' => 'BEER-CRA', 'name' => 'Bière artisanale', 'unit' => 'btl', 'price_cents' => 450, 'is_central' => false],
            ['sku' => 'DSRT-MAC', 'name' => 'Macarons', 'unit' => 'box', 'price_cents' => 1200, 'is_central' => false],
            ['sku' => 'DSRT-CHS', 'name' => 'Cheesecake', 'unit' => 'pcs', 'price_cents' => 600, 'is_central' => false],
            ['sku' => 'SAUCE-LCL', 'name' => 'Sauce locale', 'unit' => 'btl', 'price_cents' => 280, 'is_central' => false],
            ['sku' => 'SNP-CHPS', 'name' => 'Chips artisanales', 'unit' => 'pack', 'price_cents' => 250, 'is_central' => false],
            ['sku' => 'VEG-PKL', 'name' => 'Pickles maison', 'unit' => 'btl', 'price_cents' => 520, 'is_central' => false],
            ['sku' => 'SPC-PRV', 'name' => 'Mélange d\'épices', 'unit' => 'jar', 'price_cents' => 700, 'is_central' => false],
        ];

        foreach ($items as $it) {
            $exists = DB::table('stock_items')->where('sku', $it['sku'])->exists();
            if ($exists) {
                DB::table('stock_items')->where('sku', $it['sku'])->update(array_merge($it, [
                    'updated_at' => now(),
                ]));
            } else {
                DB::table('stock_items')->insert(array_merge($it, [
                    'id' => (string) \Illuminate\Support\Str::ulid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}
