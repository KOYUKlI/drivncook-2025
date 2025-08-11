<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();
    // Seed session & token
    $this->get('/forgot-password')->assertOk();
    $token = session()->token();
    $this->post('/forgot-password', [
        '_token' => $token,
        'email' => $user->email
    ], ['X-CSRF-TOKEN' => $token]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();
    $this->get('/forgot-password')->assertOk();
    $token = session()->token();
    $this->post('/forgot-password', [
        '_token' => $token,
        'email' => $user->email
    ], ['X-CSRF-TOKEN' => $token]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();
    $this->get('/forgot-password')->assertOk();
    $seedToken = session()->token();
    $this->post('/forgot-password', [
        '_token' => $seedToken,
        'email' => $user->email
    ], ['X-CSRF-TOKEN' => $seedToken]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        // Visit reset form to seed new token
        $this->get('/reset-password/'.$notification->token)->assertOk();
        $token = session()->token();
        $response = $this->post('/reset-password', [
            '_token' => $token,
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ], ['X-CSRF-TOKEN' => $token]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});
