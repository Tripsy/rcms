<?php

namespace App\Providers;

use App\Interfaces\ItemDataRepositoryInterface;
use App\Interfaces\ItemRepositoryInterface;
use App\Interfaces\ProjectRepositoryInterface;
use App\Repositories\ItemDataRepository;
use App\Repositories\ItemRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
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
