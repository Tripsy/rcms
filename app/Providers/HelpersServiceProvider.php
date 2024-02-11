<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * List with available helper files
     */
    protected array $definedHelpers = [
        'response',
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->definedHelpers as $helper) {
            require_once app_path().'/Helpers/'.$helper.'.php';
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
