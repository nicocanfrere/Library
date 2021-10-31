<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers\Factory;

use App\DataProvider\LibrarySubscriberDataProvider;
use App\Handler\Library\Subscribers\PostHandler;
use App\InputFilter\LibrarySubscriberInputFilter;
use Laminas\InputFilter\InputFilterPluginManager;
use Library\Contract\LibrarySubscriberFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PostHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager     = $container->get(InputFilterPluginManager::class);
        $inputFilter       = $pluginManager->get(LibrarySubscriberInputFilter::class);
        $subscriberFactory = $container->get(LibrarySubscriberFactoryInterface::class);
        $provider          = $container->get(LibrarySubscriberDataProvider::class);

        return new PostHandler($inputFilter, $subscriberFactory, $provider);
    }
}
