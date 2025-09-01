<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        foreach (['admin', 'warehouse', 'fleet', 'franchisee'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName], ['guard_name' => 'web']);
        }

        // Users
        $users = [
            ['name' => 'Admin', 'email' => 'admin@local.test', 'password' => 'password', 'role' => 'admin'],
            ['name' => 'Warehouse', 'email' => 'warehouse@local.test', 'password' => 'password', 'role' => 'warehouse'],
            ['name' => 'Fleet', 'email' => 'fleet@local.test', 'password' => 'password', 'role' => 'fleet'],
            ['name' => 'Franchisee 1', 'email' => 'fr1@local.test', 'password' => 'password', 'role' => 'franchisee'],
            ['name' => 'Franchisee 2', 'email' => 'fr2@local.test', 'password' => 'password', 'role' => 'franchisee'],
            ['name' => 'Franchisee 3', 'email' => 'fr3@local.test', 'password' => 'password', 'role' => 'franchisee'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
