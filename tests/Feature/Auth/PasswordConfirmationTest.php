<?php

use App\Models\User;

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    $user = User::factory()->create();
    // Seed session & CSRF token by visiting the form first
    $this->actingAs($user)->get('/confirm-password')->assertOk();
    $token = session()->token();

    $response = $this->post('/confirm-password', [
        '_token' => $token,
        'password' => 'password',
    ], [ 'X-CSRF-TOKEN' => $token ]);

    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();
    // Seed session & CSRF token
    $this->actingAs($user)->get('/confirm-password')->assertOk();
    $token = session()->token();

    $response = $this->post('/confirm-password', [
        '_token' => $token,
        'password' => 'wrong-password',
    ], [ 'X-CSRF-TOKEN' => $token ]);

    $response->assertSessionHasErrors();
});
