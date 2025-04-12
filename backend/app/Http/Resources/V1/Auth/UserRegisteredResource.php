<?php

namespace App\Http\Resources\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class UserRegisteredResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            ],
            'meta' => [
                'message' => __('User registered successfully.'),
            ],
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
