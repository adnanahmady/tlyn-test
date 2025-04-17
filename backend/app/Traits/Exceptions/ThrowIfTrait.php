<?php

namespace App\Traits\Exceptions;

trait ThrowIfTrait
{
    public static function throwIf(
        mixed $condition,
        ?string $message = null,
        ?int $statusCode = null,
    ): void {
        if ($condition) {
            $message ??= static::defaultMessage();
            $statusCode ??= static::defaultStatusCode();

            throw new static($statusCode, $message);
        }
    }

    abstract protected static function defaultMessage(): string;

    abstract protected static function defaultStatusCode(): int;
}
