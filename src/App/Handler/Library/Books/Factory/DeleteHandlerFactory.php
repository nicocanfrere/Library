<?php

declare(strict_types=1);

namespace App\Handler\Library\Books\Factory;

use App\Handler\Library\Books\DeleteHandler;
use Library\Contract\RemoveBookInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $removeBook = $container->get(RemoveBookInterface::class);

        return new DeleteHandler($removeBook);
    }
}
