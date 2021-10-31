<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers\Factory;

use App\DataProvider\LibrarySubscriberDataProvider;
use App\Handler\Library\Subscribers\ListHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ListHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $provider = $container->get(LibrarySubscriberDataProvider::class);

        return new ListHandler($provider);
    }
}
