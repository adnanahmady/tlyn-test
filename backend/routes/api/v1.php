<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(base_path('routes/api/v1/auth.php'));
Route::prefix('positions')
    ->middleware(['auth:sanctum'])
    ->group(base_path('routes/api/v1/positions.php'));
