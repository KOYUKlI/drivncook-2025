<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceLogFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-60 days', '-3 days', 'UTC');
        $closed = $this->faker->boolean(50) ? $this->faker->dateTimeBetween($start, 'now', 'UTC') : null;

        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'truck_id' => null,
            'kind' => $this->faker->randomElement(['Preventive', 'Corrective']),
            'description' => $this->faker->sentence(),
            'started_at' => $start,
            'closed_at' => $closed,
            'provider_name' => $this->faker->company(),
            'provider_contact' => $this->faker->phoneNumber(),
            'labor_cents' => $this->faker->numberBetween(5000, 45000),
            'parts_cents' => $this->faker->numberBetween(3000, 30000),
        ];
    }
}
