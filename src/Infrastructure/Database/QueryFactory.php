<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Infrastructure\Contract\QueryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class QueryFactory
{
    public function __invoke(ContainerInterface $container): QueryInterface
    {
        $connection = $container->get(DatabaseConnectionInterface::class);
        $logger     = $container->get(LoggerInterface::class);

        return new Query($connection, $logger);
    }
}
