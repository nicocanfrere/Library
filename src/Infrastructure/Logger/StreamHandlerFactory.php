<?php

declare(strict_types=1);

namespace Infrastructure\Logger;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

class StreamHandlerFactory
{
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        $config = $container->has('config')
            ? $container->get('config')
            : [];
        $config = $config['logger']['handlers']['stream_handler'] ?? [];

        return new StreamHandler(
            $config['path']
        );
    }
}
