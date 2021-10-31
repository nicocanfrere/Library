<?php

declare(strict_types=1);

namespace App\Handler\Library\Books\Factory;

use App\DataProvider\BookDataProvider;
use App\Handler\Library\Books\PutHandler;
use App\InputFilter\BookCreateInputFilter;
use Laminas\InputFilter\InputFilterPluginManager;
use Library\Contract\UpdateBookInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class PutHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(BookCreateInputFilter::class);
        $updater       = $container->get(UpdateBookInterface::class);
        $provider      = $container->get(BookDataProvider::class);
        $logger        = $container->get(LoggerInterface::class);

        return new PutHandler($inputFilter, $updater, $provider, $logger);
    }
}
