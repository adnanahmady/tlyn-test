<?php

namespace App\Support\Parents\Exceptions;

use App\Traits\Exceptions\ThrowIfTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ParentException extends HttpException
{
    use ThrowIfTrait;
}
