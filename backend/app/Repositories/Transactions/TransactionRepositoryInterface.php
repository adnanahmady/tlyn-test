<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;
use App\Support\Parents\Repositories\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends RepositoryInterface<Transaction>
 */
interface TransactionRepositoryInterface extends RepositoryInterface
{
    public function create(
        int $buyerPositionId,
        int $sellerPositionId,
        float $amount,
        int $pricePerGram,
        int $fee,
    ): Transaction;

    public function getPaginated(
        int $userId,
    ): LengthAwarePaginator;
}
