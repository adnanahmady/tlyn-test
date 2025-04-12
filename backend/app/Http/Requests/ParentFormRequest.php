<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class ParentFormRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return [];
    }
}
