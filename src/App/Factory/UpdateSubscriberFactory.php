<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\UpdateSubscriberInterface;
use Library\UseCase\UpdateSubscriber;
use Psr\Container\ContainerInterface;

class UpdateSubscriberFactory
{
    public function __invoke(ContainerInterface $container): UpdateSubscriberInterface
    {
        $repository = $container->get(LibrarySubscriberRepositoryInterface::class);

        return new UpdateSubscriber($repository);
    }
}
