<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FranchiseeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->optional()->e164PhoneNumber(),
            'billing_address' => $this->faker->address(),
            'royalty_rate' => 0.0400,
        ];
    }
}
