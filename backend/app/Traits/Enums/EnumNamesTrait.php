<?php

namespace App\Traits\Enums;

trait EnumNamesTrait
{
    public static function names(): array
    {
        return array_map(fn(self $enum) => $enum->name, self::cases());
    }
}
