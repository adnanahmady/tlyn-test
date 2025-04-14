<?php

namespace App\Traits\Enums;

trait EnumValuesTrait
{
    public static function values(): array
    {
        return array_map(fn(self $enum) => $enum->value, self::cases());
    }
}
