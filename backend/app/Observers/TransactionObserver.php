<?php

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class TransactionObserver implements ShouldDispatchAfterCommit
{
    public function saving(Transaction $transaction): void
    {
        $amount = $transaction->amount;
        $pricePerGram = $transaction->price_per_gram;
        $fee = $transaction->fee;

        $transaction->total_payment = $amount * $pricePerGram + $fee;
    }
}
