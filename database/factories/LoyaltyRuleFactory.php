<?php

namespace Database\Factories;

use App\Models\LoyaltyRule;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoyaltyRuleFactory extends Factory
{
    protected $model = LoyaltyRule::class;

    public function definition(): array
    {
        return [
            'points_per_euro' => 10,
            'redeem_rate' => 0.01,
            'expires_after_months' => 12,
            'active' => true,
        ];
    }
}
