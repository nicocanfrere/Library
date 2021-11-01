<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Infrastructure\Database\ResourceMetadata;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Psr\Container\ContainerInterface;

class LibrarySubscriberRepositoryFactory
{
    public function __invoke(ContainerInterface $container): LibrarySubscriberRepositoryInterface
    {
        $query                = $container->get(QueryInterface::class);
        $bookBorrowRepository = $container->get(BookBorrowRegistryRepositoryInterface::class);
        $config               = $container->has('config') ? $container->get('config') : [];
        $metadataConfig       = $config['db']['resource_metadata'][LibrarySubscriberRepositoryInterface::class] ?? [];
        $metadata             = (new ResourceMetadata())->loadMetadata($metadataConfig);

        return new LibrarySubscriberRepository($query, $metadata, $bookBorrowRepository);
    }
}
