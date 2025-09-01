<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseLineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'purchase_order_id' => null,
            'stock_item_id' => null,
            'qty' => $this->faker->numberBetween(1, 50),
            'unit_price_cents' => $this->faker->numberBetween(100, 5000),
            'qty_picked' => 0,
            'qty_shipped' => 0,
            'qty_delivered' => 0,
        ];
    }
}
