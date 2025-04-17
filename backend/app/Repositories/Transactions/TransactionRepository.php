<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;
use App\Support\Parents\Repositories\ParentRepository;
use Illuminate\Database\Eloquent\Model;

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
}
