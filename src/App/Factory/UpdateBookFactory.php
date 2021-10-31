<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\BookRepositoryInterface;
use Library\Contract\UpdateBookInterface;
use Library\UseCase\UpdateBook;
use Psr\Container\ContainerInterface;

class UpdateBookFactory
{
    public function __invoke(ContainerInterface $container): UpdateBookInterface
    {
        $repository = $container->get(BookRepositoryInterface::class);

        return new UpdateBook($repository);
    }
}
