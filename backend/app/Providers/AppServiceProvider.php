<?php

namespace App\Providers;

use App\Repositories\Positions\PositionRepository;
use App\Repositories\Positions\PositionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Register any application services. */
    public function register(): void {}

    /** Bootstrap any application services. */
    public function boot(): void
    {
        $this->app->bind(
            PositionRepositoryInterface::class,
            PositionRepository::class,
        );
    }
}
