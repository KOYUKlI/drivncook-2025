<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoDomainSeeder extends Seeder
{
    public function run(): void
    {

        // 4 entrepôts IDF (ex. Paris, Nanterre, Créteil, Saint-Denis)
        $whIds = [];
        foreach (['Paris', 'Nanterre', 'Créteil', 'Saint-Denis'] as $city) {
            $id = (string) Str::ulid();
            DB::table('warehouses')->insert([
                'id' => $id, 'name' => "WH-$city", 'city' => $city,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $whIds[] = $id;
        }

        // Franchise démo + 2 camions
        $fid = (string) Str::ulid();
        DB::table('franchisees')->insert([
            'id' => $fid, 'name' => 'Franchise Paris 12', 'email' => 'paris12@demo.local',
            'billing_address' => 'Paris 12e', 'royalty_rate' => 0.0400,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        foreach (['AA-123-DC', 'BB-456-DC'] as $plate) {
            DB::table('trucks')->insert([
                'id' => (string) Str::ulid(), 'plate' => $plate, 'status' => 'Active',
                'service_start' => now()->toDateString(), 'franchisee_id' => $fid,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // Articles (80% centrale)
        $items = [
            ['sku' => 'BUN-001', 'name' => 'Buns', 'unit' => 'pack', 'price_cents' => 250, 'is_central' => true],
            ['sku' => 'MEAT-001', 'name' => 'Steak', 'unit' => 'pcs', 'price_cents' => 1200, 'is_central' => true],
            ['sku' => 'SAUCE-001', 'name' => 'Sauce', 'unit' => 'btl', 'price_cents' => 300, 'is_central' => true],
            ['sku' => 'DRINK-EXT', 'name' => 'Soda local', 'unit' => 'btl', 'price_cents' => 200, 'is_central' => false],
        ];
        foreach ($items as $it) {
            DB::table('stock_items')->insert(array_merge($it, [
                'id' => (string) Str::ulid(),
                'created_at' => now(), 'updated_at' => now(),
            ]));
        }

        // Lier l'utilisateur "fr@local.test" au franchisé
        DB::table('users')->where('email', 'fr@local.test')->update(['franchisee_id' => $fid]);
    }
}
