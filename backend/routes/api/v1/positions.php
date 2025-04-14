<?php

use App\Http\Controllers\Api\V1\Positions\CreatePositionController;
use Illuminate\Support\Facades\Route;

Route::post('/', [CreatePositionController::class, 'store'])
    ->name('v1.positions.create');
