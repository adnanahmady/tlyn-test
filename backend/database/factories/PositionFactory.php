<?php

namespace Database\Factories;

use App\Models\User;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'base_amount' => $amount = fake()->randomFloat(3, 0.001, 1000),
            'amount' => $amount,
            'price_per_gram' => fake()->numberBetween(100_000_000, 100_000_000_000),
            'type' => fake()->randomElement([
                PositionType::Buy->value,
                PositionType::Sell->value,
            ]),
            'user_id' => User::factory(),
            'status' => PositionStatus::Open->value,
        ];
    }

    public function user(User $user): self
    {
        return $this->state(['user_id' => $user->getKey()]);
    }

    public function amount(float $amount): self
    {
        return $this->state(['amount' => $amount]);
    }

    public function pricePerGram(int $pricePerGram): self
    {
        return $this->state(['price_per_gram' => $pricePerGram]);
    }

    public function type(PositionType $type): self
    {
        return $this->state(['type' => $type->value]);
    }

    public function status(PositionStatus $status): self
    {
        return $this->state(['status' => $status->value]);
    }

    public function sell(): self
    {
        return $this->type(PositionType::Sell);
    }

    public function buy(): self
    {
        return $this->type(PositionType::Buy);
    }

    public function open(): self
    {
        return $this->status(PositionStatus::Open);
    }

    public function closed(): self
    {
        return $this->status(PositionStatus::Closed);
    }

    public function canceled(): self
    {
        return $this->status(PositionStatus::Canceled);
    }

    public function baseAmount(float $baseAmount): self
    {
        return $this->state(['base_amount' => $baseAmount]);
    }
}
