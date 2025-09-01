<?php

namespace Database\Seeders;

use App\Models\MaintenanceLog;
use App\Models\Truck;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $trucks = Truck::all();
        foreach ($trucks as $truck) {
            $count = random_int(1, 2);
            for ($i = 0; $i < $count; $i++) {
                $opened = now('UTC')->subDays(random_int(10, 50));
                $closed = random_int(0,1) ? now('UTC')->subDays(random_int(0, 9)) : null;
                MaintenanceLog::firstOrCreate(
                    [
                        'truck_id' => $truck->id,
                        'description' => 'Maintenance #'.$i.' for '.$truck->code,
                        'started_at' => $opened,
                    ],
                    [
                        'id' => (string) Str::ulid(),
                        'kind' => fake()->randomElement(['Preventive','Corrective']),
                        'status' => $closed ? MaintenanceLog::STATUS_CLOSED : MaintenanceLog::STATUS_PLANNED,
                        'closed_at' => $closed,
                        'provider_name' => fake()->company(),
                        'provider_contact' => fake()->phoneNumber(),
                        'labor_cents' => random_int(5000, 40000),
                        'parts_cents' => random_int(2000, 30000),
                    ]
                );
            }
        }
    }
}
