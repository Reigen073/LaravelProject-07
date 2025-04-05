<?php

use App\Models\User;
use App\Controller\ProfileController;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
    ->actingAs($user)
    ->patch('/profile', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
});

test('user can delete their account', function () {
    $password = 'password';
    $user = User::factory()->create([
        'password' => bcrypt($password),
    ]);

    $response = $this
        ->actingAs($user)
        ->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])
        ->delete(route('profile.destroy'), [
            'password' => $password,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $this->withoutMiddleware();
    $password = 'secret-password';

    $user = User::factory()->create([
        'password' => bcrypt($password),
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->post('/profile', [
            '_method' => 'DELETE',
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
