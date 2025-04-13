<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\ParentFormRequest;
use App\Rules\LoginEmailCheckRule;

class LoginRequest extends ParentFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'min:3',
                'max:255',
                new LoginEmailCheckRule($this->password),
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'max:200',
            ],
        ];
    }
}
