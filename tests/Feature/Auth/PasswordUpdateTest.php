<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = User::factory()->create();
    // Seed session & token via profile page
    $this->actingAs($user)->get('/profile');
    $token = session()->token();
    $response = $this
        ->from('/profile')
        ->put('/password', [
            '_token' => $token,
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ], ['X-CSRF-TOKEN' => $token]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/profile');
    $token = session()->token();
    $response = $this
        ->from('/profile')
        ->put('/password', [
            '_token' => $token,
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ], ['X-CSRF-TOKEN' => $token]);

    $response
        ->assertSessionHasErrorsIn('updatePassword', 'current_password')
        ->assertRedirect('/profile');
});
