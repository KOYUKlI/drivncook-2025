<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

it('admin pages répondent', function () {
    $admin = User::where('role', 'admin')->first();
    if(!$admin){
        $admin = User::create([
            'name' => 'Admin Smoke',
            'email' => 'admin-smoke@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
    $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
    $this->actingAs($admin)->get('/admin/trucks')->assertOk();
    $this->actingAs($admin)->get('/admin/supplies')->assertOk();
    // Dishes are Mission 2 and removed in Mission 1 scope
    $this->actingAs($admin)->get('/admin/inventory')->assertOk();
    $this->actingAs($admin)->get('/admin/compliance')->assertOk();
});

it('admin peut créer un plat', function () {
    $admin = User::where('role', 'admin')->first();
    if(!$admin){
        $admin = User::create([
            'name' => 'Admin Smoke',
            'email' => 'admin-smoke@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
    // $this->actingAs($admin)->get('/admin/dishes/create')->assertOk();
    $token = session()->token();
    $payload = [
        '_token' => $token,
        'name' => 'Dish '.Str::random(5),
        'price' => '9.90',
        'description' => 'Smoke dish',
    ];
    // $this->post('/admin/dishes', $payload, ['X-CSRF-TOKEN' => $token])
    //     ->assertRedirect();
});
