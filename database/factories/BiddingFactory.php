<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bidding;
use App\Models\User;
use App\Models\Advertisement;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bidding>
 */
class BiddingFactory extends Factory
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
            'advertisement_id' => Advertisement::factory(),
            'bid_amount' => $this->faker->numberBetween(100, 10000),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
