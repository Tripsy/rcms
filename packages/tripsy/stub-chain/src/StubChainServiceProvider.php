<?php

declare(strict_types=1);

namespace Tripsy\StubChain;

use Illuminate\Support\ServiceProvider;
use Tripsy\StubChain\Console\StubChain;

class StubChainServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/stub-chain.php',
            'stub-chain'
        );

        $this->loadTranslationsFrom(
            __DIR__.'/../lang',
            'stub-chain'
        );

        $this->registerPublishing();
        $this->registerCommands();
    }

    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/stub-chain.php' => config_path('stub-chain.php'),
        ], 'stub-chain');

        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs/tripsy'),
        ], 'stub-chain');

        $this->publishes([
            __DIR__.'/../lang' => lang_path(),
        ], 'stub-chain');
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                StubChain::class,
            ]);
        }
    }
}
