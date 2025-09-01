<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeploymentFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-60 days', '-1 days', 'UTC');
        $status = $this->faker->randomElement(['planned','open','closed']);
        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'truck_id' => null,
            'franchisee_id' => null,
            'location_text' => $this->faker->streetAddress(),
            'planned_start_at' => $start,
            'planned_end_at' => $this->faker->boolean(30) ? $this->faker->dateTimeBetween($start, '+3 days', 'UTC') : null,
            'actual_start_at' => $status !== 'planned' ? $this->faker->dateTimeBetween($start, 'now', 'UTC') : null,
            'actual_end_at' => $status === 'closed' ? $this->faker->dateTimeBetween('-1 days', 'now', 'UTC') : null,
            'status' => $status,
            'geo_lat' => $this->faker->randomFloat(7, 48.80, 48.95),
            'geo_lng' => $this->faker->randomFloat(7, 2.20, 2.45),
        ];
    }
}
