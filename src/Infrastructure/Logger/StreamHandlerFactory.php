<?php

declare(strict_types=1);

namespace Infrastructure\Logger;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use RuntimeException;

use function chmod;
use function is_file;
use function touch;

class StreamHandlerFactory
{
    public function __invoke(ContainerInterface $container): HandlerInterface
    {
        $config = $container->has('config')
            ? $container->get('config')
            : [];
        $config = $config['logger']['handlers']['stream_handler'] ?? [];

        if (empty($config['path'])) {
            throw new RuntimeException('Log file path is not defined!');
        }
        if (! is_file($config['path'])) {
            touch($config['path']);
            chmod($config['path'], 0777);
        }

        return new StreamHandler(
            $config['path']
        );
    }
}
