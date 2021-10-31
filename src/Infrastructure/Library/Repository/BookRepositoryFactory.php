<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class BookRepositoryFactory
{
    public function __invoke(ContainerInterface $container): BookRepositoryInterface
    {
        $connection           = $container->get(DatabaseConnectionInterface::class);
        $bookBorrowRepository = $container->get(BookBorrowRegistryRepositoryInterface::class);
        $logger               = $container->get(LoggerInterface::class);

        return new BookRepository($connection, $bookBorrowRepository, $logger);
    }
}
