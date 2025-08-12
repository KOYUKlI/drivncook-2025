<?php

namespace Database\Factories;

use App\Models\Supply;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Supply> */
class SupplyFactory extends Factory
{
    protected $model = Supply::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word()),
            'sku' => strtoupper($this->faker->bothify('SKU-####')),
            'unit' => $this->faker->randomElement(['kg','g','L','ml','pc','pack']),
            'cost' => $this->faker->randomFloat(2, 0.1, 50),
        ];
    }
}
