<?php

namespace App\Http\Requests\Api\V1\Positions;

use App\Http\Requests\ParentFormRequest;
use App\Types\Positions\PositionStatus;

class PartialUpdateSellPositionRequest extends ParentFormRequest
{
    public function authorize(): bool
    {
        return parent::authorize()
            && $this->position->isOwner($this->user());
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'in:' . join(',', PositionStatus::names())],
        ];
    }
}
