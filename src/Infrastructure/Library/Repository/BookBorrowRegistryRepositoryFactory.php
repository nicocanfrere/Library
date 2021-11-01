<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Psr\Container\ContainerInterface;

class BookBorrowRegistryRepositoryFactory
{
    public function __invoke(ContainerInterface $container): BookBorrowRegistryRepositoryInterface
    {
        $query = $container->get(QueryInterface::class);

        return new BookBorrowRegistryRepository($query);
    }
}
