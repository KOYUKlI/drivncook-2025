<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockItem;
use Illuminate\Support\Str;

class StockItemSeeder extends Seeder
{
    public function run(): void
    {
        $count = 30;
        for ($i = 0; $i < $count; $i++) {
        StockItem::firstOrCreate(
                ['sku' => sprintf('SKU-%04d', $i + 1)],
                [
            'id' => (string) Str::ulid(),
                    'name' => fake()->words(2, true),
                    'unit' => fake()->randomElement(['kg','pc','L']),
                    'price_cents' => fake()->numberBetween(100, 15000),
                    'is_central' => $i < (int) round($count * 0.6),
                    'is_active' => true,
                ]
            );
        }
    }
}

