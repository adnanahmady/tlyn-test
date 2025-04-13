<?php

namespace App\Http\Resources\V1\Auth;

use App\Dtos\V1\Auth\LoginDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoggedInResource extends JsonResource
{
    /**
     * @var LoginDto
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'access_token' => $this->resource->token(),
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $this->resource->userId(),
                    'name' => $this->resource->name(),
                    'email' => $this->resource->email(),
                ],
            ],
            'meta' => [
                'message' => __('User logged in successfully.'),
            ],
        ];
    }
}
