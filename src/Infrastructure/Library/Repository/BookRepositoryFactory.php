<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookRepositoryInterface;
use Psr\Container\ContainerInterface;

class BookRepositoryFactory
{
    public function __invoke(ContainerInterface $container): BookRepositoryInterface
    {
        $connection           = $container->get(DatabaseConnectionInterface::class);
        $bookBorrowRepository = $container->get(BookBorrowRegistryRepositoryInterface::class);

        return new BookRepository($connection, $bookBorrowRepository);
    }
}
