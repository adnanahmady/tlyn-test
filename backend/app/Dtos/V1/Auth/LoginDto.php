<?php

namespace App\Dtos\V1\Auth;

use App\Models\User;

readonly class LoginDto
{
    public function __construct(
        private User $user,
        private string $token,
    ) {}

    public function userId(): int
    {
        return $this->user->id;
    }

    public function email(): string
    {
        return $this->user->email;
    }

    public function name(): string
    {
        return $this->user->name;
    }

    public function token(): string
    {
        return $this->token;
    }
}
