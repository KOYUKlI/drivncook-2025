<?php

namespace Database\Factories;

use App\Models\Warehouse;
use App\Models\Franchise;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Warehouse> */
class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'franchise_id' => Franchise::factory(),
            'location' => $this->faker->city(),
            'name' => $this->faker->unique()->company().' Depot',
        ];
    }
}
