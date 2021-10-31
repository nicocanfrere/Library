<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Infrastructure\Contract\DatabaseConnectionInterface;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ConnectionFactory
{
    public function __invoke(ContainerInterface $container): DatabaseConnectionInterface
    {
        $logger = $container->get(LoggerInterface::class);
        $config = $container->has('config') ? $container->get('config') : [];
        if (! $config['db']) {
            throw new InvalidArgumentException('db config missing!');
        }
        if (! $config['db']['dsn']) {
            throw new InvalidArgumentException('db dsn missing!');
        }
        if (! $config['db']['username']) {
            throw new InvalidArgumentException('db username missing!');
        }
        if (! $config['db']['password']) {
            throw new InvalidArgumentException('db password missing!');
        }
        $options = $config['db']['options'] ?? [];

        return new Connection(
            $config['db']['dsn'],
            $config['db']['username'],
            $config['db']['password'],
            $options,
            $logger
        );
    }
}
