<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TruckFactory extends Factory
{
    public function definition(): array
    {
        return [
            'plate' => strtoupper($this->faker->bothify('??-###-??')),
            'status' => $this->faker->randomElement(['Active', 'Draft', 'InMaintenance']),
            'service_start' => $this->faker->optional()->date(),
            'franchisee_id' => null,
        ];
    }
}
