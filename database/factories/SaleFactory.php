<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'franchisee_id' => null,
            'sale_date' => $this->faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'total_cents' => 0,
        ];
    }
}
