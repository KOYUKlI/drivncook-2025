<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Reproducible seeding
        fake()->seed(20250831);

        // Orchestration per Mission 1
        $this->call([
            RoleAndUserSeeder::class,
            WarehouseSeeder::class,
            StockItemSeeder::class,
            WarehouseInventorySeeder::class,
            FranchiseeSeeder::class,
            TruckSeeder::class,
            MaintenanceSeeder::class,
            DeploymentSeeder::class,
            ReplenishmentSeeder::class,
            SaleSeeder::class,
            ReportPdfSeeder::class,
        ]);
    }
}
