<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'warehouse', 'fleet', 'tech', 'franchisee', 'applicant'] as $r) {
            Role::findOrCreate($r);
        }

        $admin = User::firstOrCreate(['email' => 'admin@local.test'], [
            'name' => 'Admin', 'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $wh = User::firstOrCreate(['email' => 'wh@local.test'], [
            'name' => 'Warehouse', 'password' => Hash::make('password'),
        ]);
        $wh->assignRole('warehouse');

        $fleet = User::firstOrCreate(['email' => 'fleet@local.test'], [
            'name' => 'Fleet', 'password' => Hash::make('password'),
        ]);
        $fleet->assignRole('fleet');

        $fr = User::firstOrCreate(['email' => 'fr@local.test'], [
            'name' => 'Franchise Demo', 'password' => Hash::make('password'),
        ]);
        $fr->assignRole('franchisee');
    }
}
