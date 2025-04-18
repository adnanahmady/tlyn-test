<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;
use App\Support\Parents\Repositories\ParentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends ParentRepository<Transaction>
 */
class TransactionRepository extends ParentRepository implements TransactionRepositoryInterface
{
    public function create(
        int $buyerPositionId,
        int $sellerPositionId,
        float $amount,
        int $pricePerGram,
        int $fee,
    ): Transaction {
        return Transaction::create([
            'buyer_position_id' => $buyerPositionId,
            'seller_position_id' => $sellerPositionId,
            'amount' => $amount,
            'price_per_gram' => $pricePerGram,
            'fee' => $fee,
        ]);
    }

    protected function model(): Model
    {
        return new Transaction();
    }

    public function getPaginated(
        int $userId,
    ): LengthAwarePaginator {
        return Transaction::query()
            ->where(
                fn($q) => $q
                    ->whereHas('buyerPosition', fn($q) => $q->where('user_id', $userId))
                    ->orWhereHas('sellerPosition', fn($q) => $q->where('user_id', $userId)),
            )
            ->latest('id')
            ->paginate();
    }
}
