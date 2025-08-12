<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Franchise;

class AdminFranchiseUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure an admin user exists
        User::firstOrCreate(
            ['email' => 'admin@local.test'],
            [
                'name' => 'Admin Demo',
                'password' => 'password', // cast will hash automatically
                'role' => 'admin'
            ]
        );

        // Ensure a franchise exists (simple baseline)
        $franchise = Franchise::firstOrCreate(['name' => 'Demo Franchise']);

        // Ensure a franchise user exists
        User::firstOrCreate(
            ['email' => 'franchise@local.test'],
            [
                'name' => 'Franchise Demo',
                'password' => 'password',
                'role' => 'franchise',
                'franchise_id' => $franchise->id,
            ]
        );
    }
}
