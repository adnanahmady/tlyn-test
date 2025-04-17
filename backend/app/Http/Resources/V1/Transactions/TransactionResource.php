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
            'buyer_position_id' => $this->buyer_position_id,
            'seller_position_id' => $this->seller_position_id,
            'amount' => $this->amount,
            'price_per_gram' => $this->price_per_gram,
            'fee' => $this->fee,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
