<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers\Factory;

use App\Handler\Library\Subscribers\SubscriberReturnBookHandler;
use Library\Contract\SubscriberReturnBookInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class SubscriberReturnBookHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $subscriberReturnBook = $container->get(SubscriberReturnBookInterface::class);
        $logger               = $container->get(LoggerInterface::class);

        return new SubscriberReturnBookHandler($subscriberReturnBook, $logger);
    }
}
