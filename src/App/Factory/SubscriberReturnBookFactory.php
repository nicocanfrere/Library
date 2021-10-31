<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\SubscriberReturnBookInterface;
use Library\UseCase\SubscriberReturnBook;
use Psr\Container\ContainerInterface;

class SubscriberReturnBookFactory
{
    public function __invoke(ContainerInterface $container): SubscriberReturnBookInterface
    {
        $subscriberRepository = $container->get(LibrarySubscriberRepositoryInterface::class);
        $registryRepository   = $container->get(BookBorrowRegistryRepositoryInterface::class);

        return new SubscriberReturnBook($subscriberRepository, $registryRepository);
    }
}
