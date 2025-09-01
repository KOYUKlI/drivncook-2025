<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['Draft','Approved','Picked','Shipped','Delivered','Closed']);
        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'reference' => 'REP-'.now()->format('Ym').'-'.$this->faker->unique()->numerify('####'),
            'warehouse_id' => null,
            'franchisee_id' => null,
            'placed_by' => null,
            'status' => $status,
            'kind' => 'Replenishment',
            'corp_ratio_cached' => null,
            'created_at' => $this->faker->dateTimeBetween('-60 days', 'now', 'UTC'),
            'updated_at' => now('UTC'),
            'shipped_at' => $status === 'Shipped' || $status === 'Delivered' || $status === 'Closed' ? now('UTC')->subDays($this->faker->numberBetween(1,15)) : null,
            'delivered_at' => $status === 'Delivered' || $status === 'Closed' ? now('UTC')->subDays($this->faker->numberBetween(0,5)) : null,
        ];
    }
}
