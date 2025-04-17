<?php

namespace App\Jobs;

use App\Models\Position;
use App\Repositories\Positions\PositionRepositoryInterface;
use App\Services\V1\Transactions\CreateTransactionService;
use App\Types\Positions\PositionType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateTransactionsJob implements ShouldQueue
{
    use Queueable;

    private ?Position $seller = null;
    private ?Position $buyer = null;
    private array $ignoredBuyers = [];

    public function __construct(
        private CreateTransactionService $service,
        private PositionRepositoryInterface $positionRepository,
    ) {}

    /** Execute the job. */
    public function handle(): void
    {
        $sellersCount = $this->positionRepository->countOpenPositions(
            type: PositionType::Sell,
        );
        $buyersCount = $this->positionRepository->countOpenPositions(
            type: PositionType::Buy,
        );

        if (0 === $sellersCount || 0 === $buyersCount) {
            return;
        }

        $this->makeDeals();
    }

    private function makeDeals(): void
    {
        do {
            $this->buyer = $this->positionRepository->firstOpenPosition(
                type: PositionType::Buy,
                ignoreIds: $this->ignoredBuyers,
            );

            if (!$this->buyer) {
                continue;
            }

            $this->seller = $this->positionRepository->firstOpenPosition(
                type: PositionType::Sell,
                price: $this->buyer->price_per_gram,
                ignoreUserIds: $this->buyer->user_id,
            );

            if (!$this->seller) {
                $this->ignoredBuyers[] = $this->buyer->getKey();

                continue;
            }

            $this->createTransaction();
        } while (null !== $this->buyer);
    }

    private function createTransaction(): void
    {
        $this->service->create(
            buyerId: $this->buyer->getKey(),
            sellerId: $this->seller->getKey(),
            amount: (
                $this->seller->amount >= $this->buyer->amount
            ) ? $this->buyer->amount : $this->seller->amount,
        );
    }
}
