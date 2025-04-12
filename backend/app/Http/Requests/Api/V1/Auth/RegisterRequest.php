<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\ParentFormRequest;

class RegisterRequest extends ParentFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:200'],
        ];
    }
}
