<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FranchiseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a default demo franchisee if none exists
        $exists = DB::table('franchisees')->exists();
        if ($exists) { return; }

        DB::table('franchisees')->insert([
            'id' => (string) Str::ulid(),
            'name' => 'Franchise Paris 12',
            'email' => 'paris12@demo.local',
            'phone' => '+33 1 23 45 67 89',
            'billing_address' => 'Paris 12e',
            'royalty_rate' => 0.0400,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
