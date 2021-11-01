<?php

declare(strict_types=1);

namespace App\Factory;

use Library\BookBorrowRegistryFactory;
use Library\Contract\BookBorrowRegistryFactoryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\IdentifierFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class BookBorrowRegistryFactoryFactory
{
    public function __invoke(ContainerInterface $container): BookBorrowRegistryFactoryInterface
    {
        $repository        = $container->get(BookBorrowRegistryRepositoryInterface::class);
        $identifierFactory = $container->get(IdentifierFactoryInterface::class);
        $logger            = $container->get(LoggerInterface::class);

        return new BookBorrowRegistryFactory($repository, $identifierFactory, $logger);
    }
}
