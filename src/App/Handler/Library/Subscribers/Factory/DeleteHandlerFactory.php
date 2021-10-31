<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers\Factory;

use App\Handler\Library\Subscribers\DeleteHandler;
use Library\Contract\RemoveSubscriberInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class DeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $removeSubscriber = $container->get(RemoveSubscriberInterface::class);
        $logger           = $container->get(LoggerInterface::class);

        return new DeleteHandler($removeSubscriber, $logger);
    }
}
