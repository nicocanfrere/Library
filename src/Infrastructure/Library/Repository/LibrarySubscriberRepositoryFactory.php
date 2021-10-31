<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LibrarySubscriberRepositoryFactory
{
    public function __invoke(ContainerInterface $container): LibrarySubscriberRepositoryInterface
    {
        $connection           = $container->get(DatabaseConnectionInterface::class);
        $bookBorrowRepository = $container->get(BookBorrowRegistryRepositoryInterface::class);
        $logger               = $container->get(LoggerInterface::class);

        return new LibrarySubscriberRepository($connection, $bookBorrowRepository, $logger);
    }
}
