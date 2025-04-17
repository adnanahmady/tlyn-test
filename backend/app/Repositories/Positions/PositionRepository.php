<?php

namespace App\Repositories\Positions;

use App\Models\Position;
use App\Support\Parents\Repositories\ParentRepository;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends ParentRepository<Position>
 */
class PositionRepository extends ParentRepository implements PositionRepositoryInterface
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

    public function pricePerGram(int $positionId): int
    {
        $position = Position::findOrFail($positionId);

        return $position->price_per_gram;
    }

    protected function model(): Model
    {
        return new Position();
    }
}
