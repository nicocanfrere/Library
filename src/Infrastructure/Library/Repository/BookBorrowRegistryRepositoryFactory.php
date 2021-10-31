<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class BookBorrowRegistryRepositoryFactory
{
    public function __invoke(ContainerInterface $container): BookBorrowRegistryRepositoryInterface
    {
        $connection = $container->get(DatabaseConnectionInterface::class);
        $logger     = $container->get(LoggerInterface::class);

        return new BookBorrowRegistryRepository($connection, $logger);
    }
}
