<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndUsersSeeder::class,
            DemoDomainSeeder::class,
            TruckOperationsDemoSeeder::class,
            SalesBackfillSeeder::class,
            ApplicationsDemoSeeder::class,
        ]);
    }
}
