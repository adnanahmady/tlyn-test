<?php

namespace App\Repositories\Positions;

use App\Models\Position;
use App\Support\Parents\Repositories\ParentRepository;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;
use Illuminate\Database\Eloquent\Builder;
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

    public function updateStatus(
        Position $position,
        PositionStatus $status,
    ): Position {
        $position->update([
            'status' => $status->value,
        ]);

        return $position;
    }

    public function firstOpenPosition(
        PositionType $type,
        ?int $price = null,
        int|array $ignoreIds = [],
        int|array $ignoreUserIds = [],
    ): ?Position {
        $query = $this->openPositionQuery($type, $price);

        if (!empty($ignoreIds)) {
            $ignoreIds = is_array($ignoreIds) ? $ignoreIds : [$ignoreIds];

            $query->whereNotIn('id', $ignoreIds);
        }

        if (!empty($ignoreUserIds)) {
            $ignoreUserIds = is_array($ignoreUserIds) ? $ignoreUserIds : [$ignoreUserIds];

            $query->whereNotIn('user_id', $ignoreUserIds);
        }

        return $query->first();
    }

    public function countOpenPositions(
        PositionType $type,
        ?int $price = null,
    ): int {
        return $this->openPositionQuery($type, $price)->count();
    }

    private function openPositionQuery(PositionType $type, ?int $price = null): Builder
    {
        $query = Position::query()
            ->where('amount', '>', 0)
            ->where('price_per_gram', '>', 0)
            ->where('status', PositionStatus::Open)
            ->where('type', $type)
            ->orderBy('price_per_gram');

        if ($price) {
            $query->where('price_per_gram', $price);
        }

        return $query;
    }

    protected function model(): Model
    {
        return new Position();
    }
}
