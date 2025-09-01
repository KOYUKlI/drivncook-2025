<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesBackfillSeeder extends Seeder
{
    public function run(): void
    {
    $this->command?->warn('SalesBackfillSeeder is deprecated and a no-op. Use SaleSeeder instead.');
    }
}
