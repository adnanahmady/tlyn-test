<?php

namespace App\Http\Requests\Api\V1\Positions;

use App\Http\Requests\ParentFormRequest;
use App\Types\Positions\PositionType;

class CreatePositionRequest extends ParentFormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'price_per_gram' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'string', 'in:' . implode(',', PositionType::names())],
        ];
    }
}
