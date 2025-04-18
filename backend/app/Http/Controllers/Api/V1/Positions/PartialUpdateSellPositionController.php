<?php

namespace App\Http\Controllers\Api\V1\Positions;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Positions\PositionUpdateResource;
use App\Models\Position;
use App\Services\V1\Positions\PartialUpdateSellPositionService;

class PartialUpdateSellPositionController extends Controller
{
    public function update(
        PartialUpdateSellPositionService $service,
        Position $position,
    ): PositionUpdateResource {
        $position = $service->update($position);

        return new PositionUpdateResource($position);
    }
}
