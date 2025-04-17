<?php

namespace App\Exceptions;

use App\Traits\Exceptions\ThrowIfTrait;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IncompatiblePositionsException extends HttpException
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
