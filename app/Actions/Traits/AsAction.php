<?php

namespace App\Actions\Traits;

trait AsAction
{
    /**
     * @return static
     */
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * @see static::handle()
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function run(...$arguments): mixed
    {
        return static::make()->handle(...$arguments);
    }
}
