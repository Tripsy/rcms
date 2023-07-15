<?php

declare(strict_types=1);

namespace App\Bus;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

class CommandBus
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $command
     * @return void
     * @throws BindingResolutionException
     */
    public function execute($command): void
    {
        $handler = $this->resolveHandler($command);

        $handler->handle($command);
    }

    /**
     * @param $command
     * @return object
     * @throws BindingResolutionException
     */
    protected function resolveHandler($command): object
    {
        $commandClass = get_class($command);
        $handlerClass = str_replace('Commands', 'CommandHandlers', $commandClass) . 'Handler';

        return $this->container->make($handlerClass);
    }
}
