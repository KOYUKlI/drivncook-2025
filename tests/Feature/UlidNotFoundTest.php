<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('returns 404 for invalid ULID on admin truck show', function () {
    $admin = User::where('role','admin')->first();
    if (!$admin) {
        $admin = User::create([
            'name' => 'Admin 404',
            'email' => 'admin-404@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
    $this->actingAs($admin)->get('/admin/trucks/01HZZINVALIDULID404TEST')->assertNotFound();
});
