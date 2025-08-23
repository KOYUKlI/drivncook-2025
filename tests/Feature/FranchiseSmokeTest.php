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
    $this->actingAs($fr)->get('/franchise/truckrequests')->assertOk();
    $this->actingAs($fr)->get('/franchise/sales')->assertOk();
});

it('franchise peut créer une demande de camion (mais pas créer le camion directement)', function () {
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
    // Cannot create truck directly (route disabled)
    $this->actingAs($fr)->post('/franchise/trucks', [])->assertNotFound();

    // Can create a truck request
    $this->actingAs($fr)->get('/franchise/truckrequests/create')->assertOk();
    $token = session()->token();
    $this->actingAs($fr)
        ->post('/franchise/truckrequests', [ '_token' => $token, 'reason' => 'Need capacity' ], ['X-CSRF-TOKEN' => $token])
        ->assertRedirect('/franchise/truckrequests');
});
