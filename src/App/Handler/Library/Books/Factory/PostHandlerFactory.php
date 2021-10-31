<?php

declare(strict_types=1);

namespace App\Handler\Library\Books\Factory;

use App\DataProvider\BookDataProvider;
use App\Handler\Library\Books\PostHandler;
use App\InputFilter\BookCreateInputFilter;
use Laminas\InputFilter\InputFilterPluginManager;
use Library\Contract\BookFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class PostHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(BookCreateInputFilter::class);
        $bookFactory   = $container->get(BookFactoryInterface::class);
        $provider      = $container->get(BookDataProvider::class);
        $logger        = $container->get(LoggerInterface::class);

        return new PostHandler($inputFilter, $bookFactory, $provider, $logger);
    }
}
