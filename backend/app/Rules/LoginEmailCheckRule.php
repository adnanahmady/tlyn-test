<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class LoginEmailCheckRule implements ValidationRule
{
    public function __construct(private ?string $password = null) {}

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $user = User::query()->where('email', $value)->first();

        if (!$user) {
            $fail('Email or password is incorrect.');

            return;
        }

        if (!$this->password) {
            return;
        }

        if (!Hash::check($this->password, $user->password)) {
            $fail('Email or password is incorrect.');
        }
    }
}
