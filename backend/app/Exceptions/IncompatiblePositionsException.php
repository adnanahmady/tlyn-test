<?php

namespace App\Exceptions;

use App\Support\Parents\Exceptions\ParentException;
use App\Traits\Exceptions\ThrowIfTrait;
use Illuminate\Http\Response;

class IncompatiblePositionsException extends ParentException
{
    use ThrowIfTrait;

    protected static function defaultMessage(): string
    {
        return __('Positions are not matched.');
    }

    protected static function defaultStatusCode(): int
    {
        return Response::HTTP_CONFLICT;
    }
}
