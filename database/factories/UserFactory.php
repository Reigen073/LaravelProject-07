<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make(static::$password ??= 'password'),
            'remember_token' => Str::random(10),
            'role' => fake()->randomElement(['particulier_adverteerder', 'zakelijke_adverteerder']),
            'dashboard_settings' => json_encode([
                'theme' => 'light',
                'language' => 'en',
                'notifications' => true,
            ]),
        ];
    }
}
