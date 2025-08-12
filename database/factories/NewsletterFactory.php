<?php

namespace Database\Factories;

use App\Models\Newsletter;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsletterFactory extends Factory
{
    protected $model = Newsletter::class;

    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence(4),
            'body' => $this->faker->paragraphs(3, true),
            'scheduled_at' => null,
            'sent_at' => null,
        ];
    }
}
