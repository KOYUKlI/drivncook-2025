<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\get;

it('admin can create a supply', function () {
    $admin = User::factory()->create(['role' => 'admin', 'password' => Hash::make('password')]);
    actingAs($admin);
    post(route('admin.supplies.store'), [
        'name' => 'Tomato',
        'sku' => 'SKU-0001',
        'unit' => 'kg',
        'cost' => 1.23,
    ])->assertRedirect(route('admin.supplies.index'));
});

it('admin can create a supplier', function () {
    $admin = User::factory()->create(['role' => 'admin', 'password' => Hash::make('password')]);
    actingAs($admin);
    post(route('admin.suppliers.store'), [
        'name' => 'Fresh Foods',
        'contact_email' => 'contact@example.com',
        'phone' => '0102030405',
        'is_active' => true,
    ])->assertRedirect(route('admin.suppliers.index'));
});
