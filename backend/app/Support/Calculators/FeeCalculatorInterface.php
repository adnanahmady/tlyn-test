<?php

namespace App\Support\Calculators;

interface FeeCalculatorInterface
{
    public function calculate(float $amount, int $pricePerGram): int;
}
