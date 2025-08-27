<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'warehouse_id' => null, 'franchisee_id' => null,
            'status' => $this->faker->randomElement(['Draft', 'Approved', 'Prepared', 'Shipped', 'Received']),
            'corp_ratio_cached' => null,
        ];
    }
}
