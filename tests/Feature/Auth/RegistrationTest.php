<?php
use App\Models\User;
test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $response = $this->post('/register', $user->toArray());

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
