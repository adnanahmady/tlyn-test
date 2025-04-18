<?php

namespace App\Http\Controllers\Api\V1\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Transactions\TransactionResource;
use App\Services\V1\Transactions\TransactionsListService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionsListController extends Controller
{
    public function index(TransactionsListService $service): AnonymousResourceCollection
    {
        $transactions = $service->getPaginated();

        return TransactionResource::collection($transactions);
    }
}
