<?php

declare(strict_types=1);

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            Infrastructure\Contract\DatabaseConnectionInterface::class
            => Infrastructure\Database\ConnectionFactory::class,
            Library\Contract\BookRepositoryInterface::class
            => Infrastructure\Library\Repository\BookRepositoryFactory::class,
            Library\Contract\LibrarySubscriberRepositoryInterface::class
            => Infrastructure\Library\Repository\LibrarySubscriberRepositoryFactory::class,
            Library\Contract\BookBorrowRegistryRepositoryInterface::class
            => Infrastructure\Library\Repository\BookBorrowRegistryRepositoryFactory::class,
            Library\Contract\BookFactoryInterface::class
            => App\Factory\BookFactoryFactory::class,
            Library\Contract\UpdateBookInterface::class
            => App\Factory\UpdateBookFactory::class,
            Library\Contract\RemoveBookInterface::class
            => App\Factory\RemoveBookFactory::class,
            Library\Contract\UpdateSubscriberInterface::class
            => App\Factory\UpdateSubscriberFactory::class,
            Library\Contract\RemoveSubscriberInterface::class
            => App\Factory\RemoveSubscriberFactory::class,
            Library\Contract\LibrarySubscriberFactoryInterface::class
            => App\Factory\LibrarySubscriberFactoryFactory::class,
        ],
    ],
];
