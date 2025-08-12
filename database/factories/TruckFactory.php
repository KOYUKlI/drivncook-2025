<?php

namespace Database\Factories;

use App\Models\Truck;
use App\Models\Franchise;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TruckFactory extends Factory
{
    protected $model = Truck::class;

    public function definition(): array
    {
        return [
            'franchise_id' => Franchise::factory(),
            'name' => 'Truck '.$this->faker->unique()->word(),
            'license_plate' => strtoupper(Str::random(2)).'-'.rand(100,999).'-'.strtoupper(Str::random(2)),
        ];
    }
}
