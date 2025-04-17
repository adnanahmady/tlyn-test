<?php

namespace App\Providers;

use App\Repositories\Positions\PositionRepository;
use App\Repositories\Positions\PositionRepositoryInterface;
use App\Repositories\Transactions\TransactionRepository;
use App\Repositories\Transactions\TransactionRepositoryInterface;
use App\Support\Calculators\FeeCalculator;
use App\Support\Calculators\FeeCalculatorInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Register any application services. */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    /** Bootstrap any application services. */
    public function boot(): void
    {
        $this->app->bind(
            PositionRepositoryInterface::class,
            PositionRepository::class,
        );

        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class,
        );

        $this->app->bind(
            FeeCalculatorInterface::class,
            FeeCalculator::class,
        );
    }
}
