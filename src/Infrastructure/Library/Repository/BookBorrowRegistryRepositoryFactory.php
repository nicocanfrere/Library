<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Psr\Container\ContainerInterface;

class BookBorrowRegistryRepositoryFactory
{
    public function __invoke(ContainerInterface $container): BookBorrowRegistryRepositoryInterface
    {
        $connection = $container->get(DatabaseConnectionInterface::class);

        return new BookBorrowRegistryRepository($connection);
    }
}
