<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FranchiseApplicationDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'franchise_application_id' => null,
            'kind' => $this->faker->randomElement(['id', 'business_plan', 'other']),
            'path' => 'uploads/'.$this->faker->uuid().'.pdf',
        ];
    }
}
