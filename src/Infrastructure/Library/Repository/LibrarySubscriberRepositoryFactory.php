<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LibrarySubscriberRepositoryFactory
{
    public function __invoke(ContainerInterface $container): LibrarySubscriberRepositoryInterface
    {
        $query                = $container->get(QueryInterface::class);
        $bookBorrowRepository = $container->get(BookBorrowRegistryRepositoryInterface::class);
        $logger               = $container->get(LoggerInterface::class);

        return new LibrarySubscriberRepository($query, $bookBorrowRepository, $logger);
    }
}
