<?php

namespace App\Types\Positions;

use App\Traits\Enums\EnumFromNameTrait;
use App\Traits\Enums\EnumNamesTrait;
use App\Traits\Enums\EnumValuesTrait;

enum PositionType: int
{
    use EnumValuesTrait;
    use EnumNamesTrait;
    use EnumFromNameTrait;

    case Sell = 1;
    case Buy = 2;
}
