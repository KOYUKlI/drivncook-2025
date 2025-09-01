<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'franchisee_id' => null,
            'sale_date' => $this->faker->dateTimeBetween('-60 days', 'now', 'UTC')->format('Y-m-d'),
            'total_cents' => 0,
            'created_at' => now('UTC'),
            'updated_at' => now('UTC'),
        ];
    }
}
