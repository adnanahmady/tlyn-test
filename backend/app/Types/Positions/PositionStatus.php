<?php

namespace App\Types\Positions;

use App\Traits\Enums\EnumFromNameTrait;
use App\Traits\Enums\EnumNamesTrait;
use App\Traits\Enums\EnumValuesTrait;

enum PositionStatus: int
{
    use EnumValuesTrait;
    use EnumNamesTrait;
    use EnumFromNameTrait;

    case Open = 1;
    case Closed = 2;
    case Canceled = 3;
}
