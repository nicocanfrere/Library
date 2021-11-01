<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Infrastructure\Database\ResourceMetadata;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookRepositoryInterface;
use Psr\Container\ContainerInterface;

class BookRepositoryFactory
{
    public function __invoke(ContainerInterface $container): BookRepositoryInterface
    {
        $query                = $container->get(QueryInterface::class);
        $bookBorrowRepository = $container->get(BookBorrowRegistryRepositoryInterface::class);
        $config               = $container->has('config') ? $container->get('config') : [];
        $metadataConfig       = $config['db']['resource_metadata'][BookRepositoryInterface::class] ?? [];
        $metadata             = (new ResourceMetadata())->loadMetadata($metadataConfig);

        return new BookRepository($query, $metadata, $bookBorrowRepository);
    }
}
