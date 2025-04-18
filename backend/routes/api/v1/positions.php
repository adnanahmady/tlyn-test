<?php

use App\Http\Controllers\Api\V1\Positions\CreatePositionController;
use App\Http\Controllers\Api\V1\Positions\PartialUpdateSellPositionController;
use Illuminate\Support\Facades\Route;

Route::post('/', [CreatePositionController::class, 'store'])
    ->name('v1.positions.create');

Route::patch('/sells/{position}', [PartialUpdateSellPositionController::class, 'update'])
    ->name('v1.positions.sells.update-status');
