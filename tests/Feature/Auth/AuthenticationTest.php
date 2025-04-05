<?php

use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/dashboard');
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertAuthenticated();
});

test('users can logout', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
