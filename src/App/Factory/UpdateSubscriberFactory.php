<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\UpdateSubscriberInterface;
use Library\Specification\EmailIsAvailableSpecification;
use Library\UseCase\UpdateSubscriber;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class UpdateSubscriberFactory
{
    public function __invoke(ContainerInterface $container): UpdateSubscriberInterface
    {
        $repository                    = $container->get(LibrarySubscriberRepositoryInterface::class);
        $emailIsAvailableSpecification = $container->get(EmailIsAvailableSpecification::class);
        $logger                        = $container->get(LoggerInterface::class);

        return new UpdateSubscriber($repository, $emailIsAvailableSpecification, $logger);
    }
}
