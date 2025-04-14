<?php

namespace App\Http\Resources\V1\Positions;

use App\Http\Resources\V1\Shared\Users\ReferencedUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'id' => $this->getKey(),
                'amount' => $this->amount,
                'price_per_gram' => $this->price_per_gram,
                'type' => $this->type->name,
                'status' => $this->status->name,
                'user' => new ReferencedUserResource($this->user),
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            ],
            'meta' => [
                'message' => __('Position created successfully.'),
            ],
        ];
    }
}
