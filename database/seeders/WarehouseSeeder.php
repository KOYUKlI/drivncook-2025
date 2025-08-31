<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'code' => 'WH-PAR',
                'name' => 'Entrepôt Paris',
                'city' => 'Paris',
                'region' => 'Île-de-France',
                'address' => '10 Rue de Rivoli, 75001 Paris',
                'phone' => '+33 1 23 45 67 89',
                'email' => 'paris@drivncook.local',
                'is_active' => true,
                'notes' => 'Site central Paris',
            ],
            [
                'code' => 'WH-NTR',
                'name' => 'Entrepôt Nanterre',
                'city' => 'Nanterre',
                'region' => 'Île-de-France',
                'address' => '5 Avenue Joliot-Curie, 92000 Nanterre',
                'phone' => '+33 1 98 76 54 32',
                'email' => 'nanterre@drivncook.local',
                'is_active' => true,
                'notes' => 'Plateforme Ouest',
            ],
            [
                'code' => 'WH-CRL',
                'name' => 'Entrepôt Créteil',
                'city' => 'Créteil',
                'region' => 'Île-de-France',
                'address' => '2 Rue du Général Leclerc, 94000 Créteil',
                'phone' => '+33 1 44 55 66 77',
                'email' => 'creteil@drivncook.local',
                'is_active' => true,
                'notes' => 'Plateforme Sud-Est',
            ],
            [
                'code' => 'WH-SDN',
                'name' => 'Entrepôt Saint-Denis',
                'city' => 'Saint-Denis',
                'region' => 'Île-de-France',
                'address' => '12 Boulevard Marcel Sembat, 93200 Saint-Denis',
                'phone' => '+33 1 22 33 44 55',
                'email' => 'saintdenis@drivncook.local',
                'is_active' => true,
                'notes' => 'Plateforme Nord',
            ],
        ];

        foreach ($warehouses as $wh) {
            // Prefer matching by code; if missing, try match by existing city (from older seeders)
            $existing = DB::table('warehouses')->where('code', $wh['code'])->first();
            if ($existing) {
                DB::table('warehouses')->where('id', $existing->id)->update(array_merge($wh, [
                    'updated_at' => now(),
                ]));
                continue;
            }

            $byCity = DB::table('warehouses')->where('city', $wh['city'])->first();
            if ($byCity) {
                DB::table('warehouses')->where('id', $byCity->id)->update(array_merge($wh, [
                    'updated_at' => now(),
                ]));
                continue;
            }

            DB::table('warehouses')->insert(array_merge($wh, [
                'id' => (string) Str::ulid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
