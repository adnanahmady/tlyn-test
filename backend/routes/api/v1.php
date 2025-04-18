<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(base_path('routes/api/v1/auth.php'));
Route::middleware('auth:sanctum')->group(function (): void {
    Route::prefix('positions')
        ->group(base_path('routes/api/v1/positions.php'));
    Route::prefix('users/me/transactions')
        ->group(base_path('routes/api/v1/own.transactions.php'));
});
