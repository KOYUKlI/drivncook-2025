<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeploymentFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-3 months', 'now');

        return [
            'truck_id' => null,
            'location' => $this->faker->streetAddress(),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $this->faker->boolean(30) ? $this->faker->dateTimeBetween($start, 'now')->format('Y-m-d') : null,
        ];
    }
}
