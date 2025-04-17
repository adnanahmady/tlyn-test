<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'buyer_position_id' => Position::factory(),
            'seller_position_id' => Position::factory(),
            'amount' => fake()->randomFloat(3, 0.001, 1000),
            'price_per_gram' => fake()->numberBetween(100_000_000, 100_000_000_000),
            'fee' => fake()->numberBetween(10, 100_000),
        ];
    }
}
