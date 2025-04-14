<?php

namespace App\Repositories\Positions;

use App\Models\Position;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;

interface PositionRepositoryInterface
{
    public function create(
        int $baseAmount,
        int $pricePerGram,
        PositionType $type,
        PositionStatus $status,
        int $userId,
    ): Position;
}
