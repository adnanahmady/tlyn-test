<?php

namespace App\Http\Resources\V1\Transactions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'buyer_position' => new ReferencedPositionResource($this->buyerPosition),
            'seller_position' => new ReferencedPositionResource($this->sellerPosition),
            'amount' => (float) $this->amount,
            'price_per_gram' => $this->price_per_gram * 0.1,
            'fee' => (int) round($this->fee * 0.1),
            'total_payment' => (int) round($this->total_payment * 0.1),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
