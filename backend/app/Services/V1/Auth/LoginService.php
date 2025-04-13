<?php

namespace App\Services\V1\Auth;

use App\Dtos\V1\Auth\LoginDto;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Models\User;

readonly class LoginService
{
    public function __construct(private LoginRequest $request) {}

    public function login(): LoginDto
    {
        $data = $this->request->validated();

        $user = User::query()->where('email', $data['email'])->first();

        return new LoginDto(
            user: $user,
            token: $user->createToken(config('app.key'))->plainTextToken,
        );
    }
}
