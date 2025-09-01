<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WarehouseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) Str::ulid(),
            'code' => strtoupper($this->faker->unique()->bothify('WH-???')),
            'name' => 'Entrepôt '.$this->faker->city(),
            'city' => $this->faker->city(),
            'region' => 'Île-de-France',
            'is_active' => true,
        ];
    }
}
