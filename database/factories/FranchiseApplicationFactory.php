<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FranchiseApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->e164PhoneNumber(),
            'company_name' => $this->faker->optional()->company(),
            'desired_area' => $this->faker->city(),
            'entry_fee_ack' => true, 'royalty_ack' => true, 'central80_ack' => true,
            'status' => $this->faker->randomElement(['draft', 'submitted', 'prequalified']),
            'notes' => null,
        ];
    }
}
