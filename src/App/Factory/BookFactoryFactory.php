<?php

declare(strict_types=1);

namespace App\Factory;

use Library\BookFactory;
use Library\Contract\BookFactoryInterface;
use Library\Contract\BookRepositoryInterface;
use Psr\Container\ContainerInterface;

class BookFactoryFactory
{
    public function __invoke(ContainerInterface $container): BookFactoryInterface
    {
        $repository = $container->get(BookRepositoryInterface::class);

        return new BookFactory($repository);
    }
}
