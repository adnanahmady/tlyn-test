<?php

namespace App\Services\V1\Positions;

use App\Exceptions\ForbiddenToUpdateBuyPositionException;
use App\Http\Requests\Api\V1\Positions\PartialUpdateSellPositionRequest;
use App\Models\Position;
use App\Repositories\Positions\PositionRepositoryInterface;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;

readonly class PartialUpdateSellPositionService
{
    public function __construct(
        private PartialUpdateSellPositionRequest $request,
        private PositionRepositoryInterface $repository,
    ) {}

    public function update(Position $position): Position
    {
        ForbiddenToUpdateBuyPositionException::throwIf(
            PositionType::Buy === $position->type,
        );

        $status = $this->request->validated('status');

        if ($status) {
            $position = $this->repository->updateStatus(
                position: $position,
                status: PositionStatus::fromName($status),
            );
        }

        return $position;
    }
}
