<?php

namespace Database\Factories;

use App\Models\CustomerOrder;
use App\Models\Truck;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerOrderFactory extends Factory
{
    protected $model = CustomerOrder::class;

    public function definition(): array
    {
        return [
            'truck_id' => Truck::factory(),
            'order_type' => 'on_site',
            'reference' => null,
            'total_price' => $this->faker->randomFloat(2, 5, 120),
            'ordered_at' => now(),
            'status' => 'pending',
            'payment_status' => 'pending',
        ];
    }
}
