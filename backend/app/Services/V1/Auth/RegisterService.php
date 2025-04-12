<?php

namespace App\Services\V1\Auth;

use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

readonly class RegisterService
{
    public function __construct(private RegisterRequest $request) {}

    public function register(): User
    {
        return User::create([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'password' => Hash::make($this->request->password),
        ]);
    }
}
