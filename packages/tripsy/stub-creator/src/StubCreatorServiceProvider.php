<?php

declare(strict_types=1);

namespace Tripsy\StubCreator;

use Illuminate\Support\ServiceProvider;
use Tripsy\StubCreator\Commands\MakeApiController;

class StubCreatorServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->commands(MakeApiController::class);
    }
}
