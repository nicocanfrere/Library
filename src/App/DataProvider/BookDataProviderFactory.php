<?php

declare(strict_types=1);

namespace App\DataProvider;

use App\Contract\DataProviderInterface;
use Infrastructure\Library\Repository\BookRepository;
use Library\Book;
use Library\Contract\BookRepositoryInterface;
use Mezzio\Helper\UrlHelper;
use Psr\Container\ContainerInterface;
use RuntimeException;

use function sprintf;

class BookDataProviderFactory
{
    public function __invoke(ContainerInterface $container): DataProviderInterface
    {
        if (! $container->has(BookRepositoryInterface::class)) {
            throw new RuntimeException(
                sprintf('%s is missing!', BookRepository::class)
            );
        }
        $repository = $container->get(BookRepositoryInterface::class);
        $urlHelper  = $container->get(UrlHelper::class);

        return new BookDataProvider($repository, $urlHelper, Book::class);
    }
}
