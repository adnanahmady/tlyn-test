<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidEnumCaseException extends HttpException
{
    public function __construct(
        ?string $message = null,
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0,
    ) {
        $message ??= __('The selected case is invalid.');

        parent::__construct(
            Response::HTTP_NOT_ACCEPTABLE,
            $message,
            $previous,
            $headers,
            $code,
        );
    }
}
