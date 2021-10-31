<?php

declare(strict_types=1);

namespace App\Handler\Library\Books\Factory;

use App\Handler\Library\Books\DeleteHandler;
use Library\Contract\RemoveBookInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class DeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $removeBook = $container->get(RemoveBookInterface::class);
        $logger     = $container->get(LoggerInterface::class);

        return new DeleteHandler($removeBook, $logger);
    }
}
