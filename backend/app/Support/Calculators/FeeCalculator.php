<?php

namespace App\Support\Calculators;

class FeeCalculator implements FeeCalculatorInterface
{
    private float $amount;
    private int $pricePerGram;

    public function calculate(float $amount, int $pricePerGram): int
    {
        $this->amount = $amount;
        $this->pricePerGram = $pricePerGram;

        return $this->getFee();
    }

    private function getFee(): int
    {
        $fee = $this->calculateFee();

        if ($fee <= config('fee.min')) {
            return config('fee.min');
        }

        if ($fee > config('fee.max')) {
            return config('fee.max');
        }

        return $fee;
    }

    private function calculateFee(): int
    {
        $feeInPercentage = $this->getFeeInPercentage();
        $totalPrice = $this->amount * $this->pricePerGram;

        return round($totalPrice * $feeInPercentage);
    }

    private function getFeeInPercentage(): float
    {
        if ($this->amount <= 1) {
            return config('fee.percentage.1');
        }

        if ($this->amount <= 10) {
            return config('fee.percentage.10');
        }

        return config('fee.percentage.10+');
    }
}
