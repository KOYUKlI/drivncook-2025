<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('export pdf ventes répond', function () {
    $admin = User::where('role', 'admin')->first();
    if(!$admin){
        $admin = User::create([
            'name' => 'Admin Smoke',
            'email' => 'admin-smoke@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
    $this->actingAs($admin)
        ->get(route('admin.exports.sales.pdf'))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('commande commissions:compute passe', function () {
    $this->artisan('commissions:compute')->assertExitCode(0);
});
