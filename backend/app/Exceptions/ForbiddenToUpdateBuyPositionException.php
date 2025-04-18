<?php

namespace App\Exceptions;

use App\Support\Parents\Exceptions\ParentException;
use Illuminate\Http\Response;

class ForbiddenToUpdateBuyPositionException extends ParentException
{
    protected static function defaultMessage(): string
    {
        return __('Only sell positions are allowed to edit.');
    }

    protected static function defaultStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
