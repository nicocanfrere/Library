<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers\Factory;

use App\DataProvider\LibrarySubscriberDataProvider;
use App\Handler\Library\Subscribers\PutHandler;
use App\InputFilter\LibrarySubscriberInputFilter;
use Laminas\InputFilter\InputFilterPluginManager;
use Library\Contract\UpdateSubscriberInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PutHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(LibrarySubscriberInputFilter::class);
        $updater       = $container->get(UpdateSubscriberInterface::class);
        $provider      = $container->get(LibrarySubscriberDataProvider::class);
        return new PutHandler($inputFilter, $updater, $provider);
    }
}
