<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $this->get('/register')->assertOk();
    $token = session()->token();
    $response = $this->post('/register', [
        '_token' => $token,
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], ['X-CSRF-TOKEN' => $token]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
