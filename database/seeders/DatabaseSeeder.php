<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\MinimalSeed;
use Database\Seeders\DemoSeed;
use Database\Seeders\BulkSeed;
use Database\Seeders\AdminFranchiseUsersSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    // Unified seeding entry point (supports profiles via SEED_PROFILE env)
    $this->call(BaselineSeeder::class);
    }
}
