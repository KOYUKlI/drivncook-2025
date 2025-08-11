<?php

use App\Models\{User, Franchise, Truck};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

it('franchise pages répondent', function () {
    $fr = User::where('role','franchise')->whereNotNull('franchise_id')->first();
    if(!$fr){
        $franchise = Franchise::create(['name' => 'Fr Smoke '.Str::random(4)]);
        $fr = User::create([
            'name' => 'Fr User',
            'email' => 'fr-smoke@example.test',
            'password' => Hash::make('password'),
            'role' => 'franchise',
            'franchise_id' => $franchise->id,
        ]);
    }
    $this->actingAs($fr)->get('/franchise/dashboard')->assertOk();
    $this->actingAs($fr)->get('/franchise/trucks')->assertOk();
    $this->actingAs($fr)->get('/franchise/stockorders')->assertOk();
});

it('franchise peut créer un truck', function () {
    $fr = User::where('role','franchise')->whereNotNull('franchise_id')->first();
    if(!$fr){
        $franchise = Franchise::create(['name' => 'Fr Smoke '.Str::random(4)]);
        $fr = User::create([
            'name' => 'Fr User',
            'email' => 'fr-smoke@example.test',
            'password' => Hash::make('password'),
            'role' => 'franchise',
            'franchise_id' => $franchise->id,
        ]);
    }
    $plate = strtoupper(Str::random(2)).'-'.rand(100,999).'-'.strtoupper(Str::random(2));
    // Seed session & token by visiting create form (if exists) or listing
    $this->actingAs($fr)->get('/franchise/trucks')->assertOk();
    $token = session()->token();
    $this->actingAs($fr)
        ->post('/franchise/trucks', [
            '_token' => $token,
            'name' => 'Truck '.Str::random(4),
            'license_plate' => $plate,
        ], ['X-CSRF-TOKEN' => $token])
        ->assertRedirect('/franchise/trucks');
});
