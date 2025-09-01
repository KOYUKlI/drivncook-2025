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
    $this->command?->warn('RolesAndUsersSeeder is deprecated and a no-op. Use RoleAndUserSeeder instead.');
    }
}
