<?php

namespace App\Services\V1\Transactions;

use App\Exceptions\IncompatiblePositionsException;
use App\Models\Position;
use App\Repositories\Positions\PositionRepositoryInterface;
use App\Repositories\Transactions\TransactionRepositoryInterface;
use App\Support\Calculators\FeeCalculatorInterface;
use App\Types\Positions\PositionStatus;
use Illuminate\Support\Facades\DB;

class CreateTransactionService
{
    private Position $buyer;
    private Position $seller;
    private float $amount;

    public function __construct(
        private readonly TransactionRepositoryInterface $repository,
        private readonly PositionRepositoryInterface $positionRepository,
        private readonly FeeCalculatorInterface $feeCalculator,
    ) {}

    public function create(
        int $buyerId,
        int $sellerId,
        float $amount,
    ): void {
        $this->amount = $amount;

        try {
            DB::beginTransaction();

            $this->ceilBuyer($buyerId);
            $this->ceilSeller($sellerId);

            IncompatiblePositionsException::throwIf(
                $this->buyer->price_per_gram !== $this->seller->price_per_gram,
                'Prices are not equal.',
            );

            $this->updateBuyer();
            $this->updateSeller();
            $this->storeTransaction();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function ceilBuyer(int $buyerId): void
    {
        $this->buyer = $this->positionRepository->findAndLock($buyerId);
    }

    private function ceilSeller(int $sellerId): void
    {
        $this->seller = $this->positionRepository->findAndLock($sellerId);
    }

    private function updateBuyer(): void
    {
        $newAmount = $this->buyer->amount - $this->amount;
        $this->buyer->update([
            'amount' => $newAmount,
            'status' => 0 == $newAmount
                ? PositionStatus::Closed
                : PositionStatus::Open,
        ]);
    }

    private function updateSeller(): void
    {
        $this->seller->update([
            'amount' => $this->seller->amount - $this->amount,
        ]);
    }

    private function storeTransaction(): void
    {
        $this->repository->create(
            buyerPositionId: $this->buyer->getKey(),
            sellerPositionId: $this->seller->getKey(),
            amount: $this->amount,
            pricePerGram: $this->buyer->price_per_gram,
            fee: $this->feeCalculator->calculate(
                amount: $this->amount,
                pricePerGram: $this->buyer->price_per_gram,
            ),
        );
    }
}
