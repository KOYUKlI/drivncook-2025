<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StockItemFactory extends Factory
{
    public function definition(): array
    {
        $units = ['kg','pc','L'];
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####')),
            'name' => $this->faker->words(2, true),
            'unit' => $this->faker->randomElement($units),
            'price_cents' => $this->faker->numberBetween(100, 5000),
            'is_central' => $this->faker->boolean(60),
            'is_active' => true,
            'created_at' => $this->faker->dateTimeBetween('-60 days', 'now', 'UTC'),
            'updated_at' => now('UTC'),
        ];
    }
}
