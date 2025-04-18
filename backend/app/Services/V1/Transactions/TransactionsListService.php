<?php

namespace App\Services\V1\Transactions;

use App\Repositories\Transactions\TransactionRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionsListService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $repository,
    ) {}

    public function getPaginated(): LengthAwarePaginator
    {
        $userId = request()->user()->getKey();

        return $this->repository->getPaginated($userId);
    }
}
