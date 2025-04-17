<?php

namespace App\Http\Controllers\Api\V1\Positions;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Positions\PositionCreatedResource;
use App\Services\V1\Positions\CreatePositionService;

class CreatePositionController extends Controller
{
    public function store(CreatePositionService $service): PositionCreatedResource
    {
        $position = $service->create();

        return new PositionCreatedResource($position);
    }
}
