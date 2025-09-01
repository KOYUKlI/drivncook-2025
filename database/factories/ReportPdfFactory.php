<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReportPdfFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'franchisee_id' => null,
            'type' => 'monthly_sales',
            'year' => now()->year,
            'month' => now()->month,
            'storage_path' => 'reports/'.now()->format('Ym').'-demo.pdf',
            'generated_at' => now('UTC'),
        ];
    }
}
