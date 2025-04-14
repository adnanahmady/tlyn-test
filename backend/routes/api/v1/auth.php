<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register'])
    ->name('v1.auth.register');

Route::post('/login', [LoginController::class, 'login'])
    ->name('v1.auth.login');
