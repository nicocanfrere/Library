<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Helper\UrlHelper;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function var_dump;

class IndexHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $urlh = $container->get(UrlHelper::class);
        $path = $urlh->generate('subscribers.list');
        var_dump($path);
        die();
        return new IndexHandler();
    }
}
