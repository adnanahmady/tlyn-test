<?php

namespace App\Http\Resources\V1\Positions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionCreatedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data' => new PositionResource($this->resource),
            'meta' => [
                'message' => __('Position created successfully.'),
            ],
        ];
    }
}
