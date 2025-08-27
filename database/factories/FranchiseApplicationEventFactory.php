<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FranchiseApplicationEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'franchise_application_id' => null,
            'user_id' => null,
            'from_status' => null,
            'to_status' => $this->faker->randomElement(['submitted', 'prequalified', 'interview']),
            'message' => $this->faker->sentence(),
        ];
    }
}
