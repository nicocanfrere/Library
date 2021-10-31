<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\BookRepositoryInterface;
use Library\Contract\UpdateBookInterface;
use Library\UseCase\UpdateBook;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class UpdateBookFactory
{
    public function __invoke(ContainerInterface $container): UpdateBookInterface
    {
        $repository = $container->get(BookRepositoryInterface::class);
        $logger     = $container->get(LoggerInterface::class);

        return new UpdateBook($repository, $logger);
    }
}
