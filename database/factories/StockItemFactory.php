<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StockItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####')),
            'name' => $this->faker->word(),
            'unit' => 'pcs',
            'price_cents' => $this->faker->numberBetween(100, 5000),
            'is_central' => $this->faker->boolean(80),
        ];
    }
}
