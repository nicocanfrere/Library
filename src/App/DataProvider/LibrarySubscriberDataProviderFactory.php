<?php

declare(strict_types=1);

namespace App\DataProvider;

use App\Contract\DataProviderInterface;
use Infrastructure\Library\Repository\LibrarySubscriberRepository;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\LibrarySubscriber;
use Mezzio\Helper\UrlHelper;
use Psr\Container\ContainerInterface;
use RuntimeException;

use function sprintf;

class LibrarySubscriberDataProviderFactory
{
    public function __invoke(ContainerInterface $container): DataProviderInterface
    {
        if (! $container->has(LibrarySubscriberRepositoryInterface::class)) {
            throw new RuntimeException(
                sprintf('%s is missing!', LibrarySubscriberRepository::class)
            );
        }
        $repository = $container->get(LibrarySubscriberRepositoryInterface::class);
        $urlHelper  = $container->get(UrlHelper::class);

        return new LibrarySubscriberDataProvider($repository, $urlHelper, LibrarySubscriber::class);
    }
}
