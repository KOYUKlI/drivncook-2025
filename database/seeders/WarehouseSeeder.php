<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use Illuminate\Support\Str;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            ['code' => 'WH-PAR', 'name' => 'Paris Centre', 'city' => 'Paris', 'region' => 'IDF'],
            ['code' => 'WH-NE', 'name' => 'Nord-Est', 'city' => 'Saint-Denis', 'region' => 'IDF'],
            ['code' => 'WH-SUD', 'name' => 'Sud', 'city' => 'Vitry-sur-Seine', 'region' => 'IDF'],
            ['code' => 'WH-OUEST', 'name' => 'Ouest', 'city' => 'Nanterre', 'region' => 'IDF'],
        ];

        foreach ($warehouses as $w) {
            Warehouse::firstOrCreate(
                ['code' => $w['code']],
                [
                    'id' => (string) Str::ulid(),
                    'name' => $w['name'],
                    'city' => $w['city'],
                    'region' => $w['region'],
                    'is_active' => true,
                ]
            );
        }
    }
}
