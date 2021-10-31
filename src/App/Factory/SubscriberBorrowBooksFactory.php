<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\BookBorrowRegistryFactoryInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\SubscriberBorrowBooksInterface;
use Library\UseCase\SubscriberBorrowBooks;
use Psr\Container\ContainerInterface;

class SubscriberBorrowBooksFactory
{
    public function __invoke(ContainerInterface $container): SubscriberBorrowBooksInterface
    {
        $subscriberRepository = $container->get(LibrarySubscriberRepositoryInterface::class);
        $bookRepository       = $container->get(BookRepositoryInterface::class);
        $registryFactory      = $container->get(BookBorrowRegistryFactoryInterface::class);

        return new SubscriberBorrowBooks(
            $subscriberRepository,
            $bookRepository,
            $registryFactory
        );
    }
}
