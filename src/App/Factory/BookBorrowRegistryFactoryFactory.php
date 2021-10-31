<?php

declare(strict_types=1);

namespace App\Factory;

use Library\BookBorrowRegistryFactory;
use Library\Contract\BookBorrowRegistryFactoryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Psr\Container\ContainerInterface;

class BookBorrowRegistryFactoryFactory
{
    public function __invoke(ContainerInterface $container): BookBorrowRegistryFactoryInterface
    {
        $repository = $container->get(BookBorrowRegistryRepositoryInterface::class);

        return new BookBorrowRegistryFactory($repository);
    }
}
