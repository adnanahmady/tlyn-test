<?php

namespace App\Repositories\Positions;

use App\Models\Position;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;

class PositionRepository implements PositionRepositoryInterface
{
    public function create(
        int $baseAmount,
        int $pricePerGram,
        PositionType $type,
        PositionStatus $status,
        int $userId,
    ): Position {
        return Position::create([
            'base_amount' => $baseAmount,
            'amount' => $baseAmount,
            'price_per_gram' => $pricePerGram,
            'type' => $type->value,
            'status' => $status->value,
            'user_id' => $userId,
        ]);
    }
}
