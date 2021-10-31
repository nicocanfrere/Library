<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\BookFactoryInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\RemoveBookInterface;
use Library\UseCase\RemoveBook;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class RemoveBookFactory
{
    public function __invoke(ContainerInterface $container): RemoveBookInterface
    {
        $repository = $container->get(BookRepositoryInterface::class);
        $factory    = $container->get(BookFactoryInterface::class);
        $logger     = $container->get(LoggerInterface::class);

        return new RemoveBook($repository, $factory, $logger);
    }
}
