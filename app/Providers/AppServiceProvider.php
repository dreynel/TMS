<?php

namespace App\Providers;

use App\Contracts\BorrowingRepositoryInterface;
use App\Contracts\ToolRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\BorrowingRepository;
use App\Repositories\ToolRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ToolRepositoryInterface::class, ToolRepository::class);
        $this->app->bind(BorrowingRepositoryInterface::class, BorrowingRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
