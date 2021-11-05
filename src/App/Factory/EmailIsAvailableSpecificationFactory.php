<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Specification\EmailIsAvailableSpecification;
use Psr\Container\ContainerInterface;

class EmailIsAvailableSpecificationFactory
{
    public function __invoke(ContainerInterface $container): EmailIsAvailableSpecification
    {
        $librarySubscriberRepository = $container->get(LibrarySubscriberRepositoryInterface::class);

        return new EmailIsAvailableSpecification($librarySubscriberRepository);
    }
}
