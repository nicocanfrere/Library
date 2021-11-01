<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class BookRepositoryFactory
{
    public function __invoke(ContainerInterface $container): BookRepositoryInterface
    {
        $query                = $container->get(QueryInterface::class);
        $bookBorrowRepository = $container->get(BookBorrowRegistryRepositoryInterface::class);
        $logger               = $container->get(LoggerInterface::class);

        return new BookRepository($query, $bookBorrowRepository, $logger);
    }
}
