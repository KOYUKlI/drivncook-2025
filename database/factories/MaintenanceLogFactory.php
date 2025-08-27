<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceLogFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-2 months', '-1 week');
        $closed = $this->faker->boolean(70) ? $this->faker->dateTimeBetween($start, 'now') : null;

        return [
            'truck_id' => null,
            'kind' => $this->faker->randomElement(['Preventive', 'Corrective']),
            'description' => $this->faker->sentence(),
            'started_at' => $start,
            'closed_at' => $closed,
        ];
    }
}
