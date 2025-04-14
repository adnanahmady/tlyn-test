<?php

namespace App\Traits\Enums;

use App\Exceptions\InvalidEnumCaseException;

trait EnumFromNameTrait
{
    public static function fromName(string $name): self
    {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new InvalidEnumCaseException();
    }
}
