<?php

use App\Http\Controllers\Api\V1\Transactions\TransactionsListController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TransactionsListController::class, 'index'])
    ->name('v1.own.transactions.index');
