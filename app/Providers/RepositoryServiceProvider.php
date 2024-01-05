<?php

namespace App\Providers;

use App\Repositories\Interfaces\ItemDataRepositoryInterface;
use App\Repositories\Interfaces\ItemRepositoryInterface;
use App\Repositories\ItemDataRepository;
use App\Repositories\ItemRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(ItemDataRepositoryInterface::class, ItemDataRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
