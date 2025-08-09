<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Franchise;
use App\Models\Warehouse;
use App\Models\Supply;
use Illuminate\Support\Facades\DB;

class MinimalSeed extends Seeder
{
    public function run(): void
    {
        $franchise = Franchise::first() ?? Franchise::create(['name' => 'Paris HQ']);
        Warehouse::firstOrCreate(['name' => 'Entrepôt Est', 'franchise_id' => $franchise->id], ['location' => 'Paris 12']);
        Warehouse::firstOrCreate(['name' => 'Entrepôt Ouest', 'franchise_id' => $franchise->id], ['location' => 'Paris 16']);

        Supply::firstOrCreate(['name' => 'Pain burger'], ['unit' => 'pc', 'cost' => 0.40]);
        Supply::firstOrCreate(['name' => 'Steak 150g'], ['unit' => 'kg', 'cost' => 9.50]);
        Supply::firstOrCreate(['name' => 'Cheddar'], ['unit' => 'kg', 'cost' => 7.80]);

        DB::table('loyalty_rules')->insertOrIgnore([
            'id' => 1,
            'points_per_euro' => 1.00,
            'redeem_rate' => 100.00,
            'expires_after_months' => null,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
