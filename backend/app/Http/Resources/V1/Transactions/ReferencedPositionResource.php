<?php

namespace App\Http\Resources\V1\Transactions;

use App\Http\Resources\V1\Shared\Users\ReferencedUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferencedPositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'type' => $this->type->name,
            'status' => $this->status->name,
            'user' => new ReferencedUserResource($this->user),
        ];
    }
}
