<?php

namespace Database\Seeders;

use App\Models\Franchisee;
use App\Models\Truck;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TruckSeeder extends Seeder
{
    public function run(): void
    {
        $franchisees = Franchisee::pluck('id')->all();
        if (empty($franchisees)) {
            return;
        }

        $statuses = ['Draft','Active','InMaintenance','Retired'];
        for ($i = 1; $i <= 6; $i++) {
            $code = 'TRK-'.str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $truck = Truck::firstOrCreate(
                ['code' => $code],
                [
                    'id' => (string) Str::ulid(),
                    'name' => 'Truck '.$i,
                    'plate' => strtoupper(fake()->bothify('??-###-??')),
                    'vin' => strtoupper(fake()->bothify('VF1###########')),
                    'make' => fake()->randomElement(['Renault','Peugeot','Citroën','Mercedes']),
                    'model' => fake()->randomElement(['Master','Boxer','Jumpy','Sprinter']),
                    'year' => (int) fake()->numberBetween(date('Y')-8, date('Y')),
                    'status' => $statuses[$i % count($statuses)],
                    'acquired_at' => now('UTC')->subMonths(random_int(4, 24))->startOfDay(),
                    'service_start' => now('UTC')->subMonths(random_int(1, 12))->startOfDay(),
                    'mileage_km' => random_int(20000, 200000),
                    'franchisee_id' => $franchisees[($i-1) % count($franchisees)],
                    'registration_doc_path' => 'private/docs/'.Str::random(8).'.pdf',
                    'insurance_doc_path' => 'private/docs/'.Str::random(8).'.pdf',
                ]
            );
        }
    }
}
