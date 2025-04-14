<?php

namespace App\Http\Controllers\Api\V1\Positions;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Positions\PositionResource;
use App\Services\V1\Positions\CreatePositionService;

class CreatePositionController extends Controller
{
    public function store(CreatePositionService $service): PositionResource
    {
        $position = $service->create();

        return new PositionResource($position);
    }
}
