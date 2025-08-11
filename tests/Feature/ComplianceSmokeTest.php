<?php

use App\Models\{User, Franchise};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

it('compliance edit et mise à jour fonctionnent', function () {
    $admin = User::where('role', 'admin')->first();
    if(!$admin){
        $admin = User::create([
            'name' => 'Admin Smoke',
            'email' => 'admin-smoke@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
    $franchise = Franchise::first();
    if(!$franchise){
        $franchise = Franchise::create(['name' => 'Fr Smoke '.Str::random(4)]);
    }
    $now = now();

    $this->actingAs($admin)
        ->get(route('admin.compliance.edit', ['franchisee' => $franchise, 'year' => $now->year, 'month' => $now->month]))
        ->assertOk();
    $token = session()->token();
    $this->actingAs($admin)
        ->put(route('admin.compliance.update', ['franchisee' => $franchise]), [
            '_token' => $token,
            'year' => $now->year,
            'month' => $now->month,
            'external_turnover' => '1234.56',
        ], ['X-CSRF-TOKEN' => $token])
        ->assertRedirect(route('admin.compliance.index', ['year' => $now->year, 'month' => $now->month]));
});
