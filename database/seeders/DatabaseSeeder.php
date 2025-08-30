<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Check if we should use the test seeder instead of demo
        $useTestData = env('SEED_TEST_DATA', false);
        
        $this->call([
            RolesAndUsersSeeder::class,
            DemoDomainSeeder::class,
            $useTestData ? TestTruckOperationsSeeder::class : TruckOperationsDemoSeeder::class,
            SalesBackfillSeeder::class,
            ApplicationsDemoSeeder::class,
        ]);
    }
}
