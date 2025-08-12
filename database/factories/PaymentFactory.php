<?php

namespace Database\Factories;

use App\Models\CustomerOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_order_id' => CustomerOrder::factory(),
            'amount' => $this->faker->randomFloat(2, 5, 120),
            'method' => $this->faker->randomElement(['card','cash','voucher']),
            'provider_ref' => $this->faker->uuid(),
            'status' => 'pending',
        ];
    }

    public function captured(): self
    {
        return $this->state(fn() => [
            'status' => 'captured',
            'captured_at' => now(),
        ]);
    }
}
