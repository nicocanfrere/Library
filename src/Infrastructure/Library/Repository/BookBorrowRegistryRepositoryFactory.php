<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Infrastructure\Database\ResourceMetadata;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Psr\Container\ContainerInterface;

class BookBorrowRegistryRepositoryFactory
{
    public function __invoke(ContainerInterface $container): BookBorrowRegistryRepositoryInterface
    {
        $query          = $container->get(QueryInterface::class);
        $config         = $container->has('config') ? $container->get('config') : [];
        $metadataConfig = $config['db']['resource_metadata'][BookBorrowRegistryRepositoryInterface::class] ?? [];
        $metadata       = (new ResourceMetadata())->loadMetadata($metadataConfig);

        return new BookBorrowRegistryRepository($query, $metadata);
    }
}
