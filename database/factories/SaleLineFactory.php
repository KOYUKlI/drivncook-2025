<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleLineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sale_id' => null,
            'stock_item_id' => null,
            'qty' => $this->faker->numberBetween(1, 10),
            'unit_price_cents' => $this->faker->numberBetween(100, 3000),
        ];
    }
}
