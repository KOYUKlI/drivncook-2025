<?php

namespace Database\Seeders;

use App\Models\Truck;
use App\Models\TruckDeployment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DeploymentSeeder extends Seeder
{
    public function run(): void
    {
        $trucks = Truck::all();
        foreach ($trucks as $truck) {
            $periods = [];
            $slots = random_int(1, 3);
            for ($i = 0; $i < $slots; $i++) {
                $start = now('UTC')->subDays(30)->addDays($i * 7);
                $end = (clone $start)->addDays(2);
                $periods[] = [$start, $end];
            }
            foreach ($periods as [$start, $end]) {
                TruckDeployment::firstOrCreate(
                    [
                        'truck_id' => $truck->id,
                        'planned_start_at' => $start,
                    ],
                    [
                        'id' => (string) Str::ulid(),
                        'franchisee_id' => $truck->franchisee_id,
                        'location_text' => fake()->streetAddress(),
                        'planned_end_at' => $end,
                        'actual_start_at' => $start,
                        'actual_end_at' => random_int(0,1) ? $end : null,
                        'status' => random_int(0,1) ? 'closed' : 'open',
                        'geo_lat' => fake()->randomFloat(7, 48.80, 48.95),
                        'geo_lng' => fake()->randomFloat(7, 2.20, 2.45),
                    ]
                );
            }
        }
    }
}
