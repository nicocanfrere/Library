<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\LibrarySubscriberFactoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\RemoveSubscriberInterface;
use Library\UseCase\RemoveSubscriber;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class RemoveSubscriberFactory
{
    public function __invoke(ContainerInterface $container): RemoveSubscriberInterface
    {
        $repository = $container->get(LibrarySubscriberRepositoryInterface::class);
        $factory    = $container->get(LibrarySubscriberFactoryInterface::class);
        $logger     = $container->get(LoggerInterface::class);

        return new RemoveSubscriber($repository, $factory, $logger);
    }
}
