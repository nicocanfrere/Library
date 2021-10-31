<?php

declare(strict_types=1);

namespace Infrastructure\Logger;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $config = $container->has('config')
            ? $container->get('config')
            : [];
        $config = $config['logger'] ?? [];

        $configHandlers = $config['handlers'];
        $handlers       = [];
        foreach ($configHandlers as $handler => $params) {
            if ($container->has($handler)) {
                $handlers[] = $container->get($handler);
            }
        }

        return new Logger(
            $config['name'],
            $handlers
        );
    }
}
