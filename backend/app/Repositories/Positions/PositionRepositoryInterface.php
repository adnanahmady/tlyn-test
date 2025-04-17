<?php

namespace App\Repositories\Positions;

use App\Models\Position;
use App\Support\Parents\Repositories\RepositoryInterface;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;

/**
 * @extends RepositoryInterface<Position>
 */
interface PositionRepositoryInterface extends RepositoryInterface
{
    public function create(
        int $baseAmount,
        int $pricePerGram,
        PositionType $type,
        PositionStatus $status,
        int $userId,
    ): Position;

    public function firstOpenPosition(
        PositionType $type,
        ?int $price = null,
        int|array $ignoreIds = [],
        int|array $ignoreUserIds = [],
    ): ?Position;

    public function countOpenPositions(
        PositionType $type,
        ?int $price = null,
    ): int;
}
