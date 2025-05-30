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
            'id' => $this->getKey(),
            'amount' => (float) $this->amount,
            'price_per_gram' => (int) round($this->price_per_gram * 0.1),
            'type' => $this->type->name,
            'status' => $this->status->name,
            'user' => new ReferencedUserResource($this->user),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
