<?php

namespace Database\Factories;

use App\Models\FranchiseApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FranchiseApplication>
 */
class FranchiseApplicationFactory extends Factory
{
    protected $model = FranchiseApplication::class;

    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'city' => $this->faker->city(),
            'budget' => $this->faker->numberBetween(50000, 120000),
            'experience' => $this->faker->sentence(8),
            'motivation' => $this->faker->paragraph(),
            'status' => 'pending',
        ];
    }
}
