<?php

use Tests\Helpers\CsrfTestHelpers;

uses(CsrfTestHelpers::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->postWithCsrf('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], '/register');

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
