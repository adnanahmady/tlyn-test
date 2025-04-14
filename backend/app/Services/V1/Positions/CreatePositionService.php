<?php

namespace App\Services\V1\Positions;

use App\Http\Requests\Api\V1\Positions\CreatePositionRequest;
use App\Models\Position;
use App\Repositories\Positions\PositionRepositoryInterface;
use App\Types\Positions\PositionStatus;
use App\Types\Positions\PositionType;

class CreatePositionService
{
    public function __construct(
        private readonly CreatePositionRequest $request,
        private readonly PositionRepositoryInterface $repository,
    ) {}

    public function create(): Position
    {
        $data = $this->request->validated();

        return $this->repository->create(
            baseAmount: $data['amount'],
            pricePerGram: $data['price_per_gram'],
            type: PositionType::fromName($data['type']),
            status: PositionStatus::Open,
            userId: $this->request->user()->getKey(),
        );
    }
}
