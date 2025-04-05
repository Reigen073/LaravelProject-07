<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'category' => $this->faker->word,
            'wear_rate' => $this->faker->numberBetween(1, 10),
            'type' => $this->faker->randomElement(['buy', 'rent', 'bidding']),
            'status' => $this->faker->randomElement(['available', 'rented', 'sold']),
            'qr_code' => Str::uuid()->toString(),
            'image' => $this->faker->imageUrl(640, 480, 'products', true),
            'condition' => $this->faker->randomElement(['new', 'used', 'refurbished']),
            'expires_at' => now()->addDays($this->faker->numberBetween(5, 30)),
            'acquirer_user_id' => null,
        ];
    }
}
