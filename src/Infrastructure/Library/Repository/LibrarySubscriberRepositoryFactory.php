<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Psr\Container\ContainerInterface;

class LibrarySubscriberRepositoryFactory
{
    public function __invoke(ContainerInterface $container): LibrarySubscriberRepositoryInterface
    {
        $connection           = $container->get(DatabaseConnectionInterface::class);
        $bookBorrowRepository = $container->get(BookBorrowRegistryRepositoryInterface::class);

        return new LibrarySubscriberRepository($connection, $bookBorrowRepository);
    }
}
