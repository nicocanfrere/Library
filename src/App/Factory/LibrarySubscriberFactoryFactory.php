<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\LibrarySubscriberFactoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\LibrarySubscriberFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LibrarySubscriberFactoryFactory
{
    public function __invoke(ContainerInterface $container): LibrarySubscriberFactoryInterface
    {
        $repository = $container->get(LibrarySubscriberRepositoryInterface::class);
        $logger     = $container->get(LoggerInterface::class);

        return new LibrarySubscriberFactory($repository, $logger);
    }
}
