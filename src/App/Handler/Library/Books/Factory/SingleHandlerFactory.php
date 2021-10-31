<?php

declare(strict_types=1);

namespace App\Handler\Library\Books\Factory;

use App\DataProvider\BookDataProvider;
use App\Handler\Library\Books\SingleHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SingleHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $provider = $container->get(BookDataProvider::class);

        return new SingleHandler($provider);
    }
}
