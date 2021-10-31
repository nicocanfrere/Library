<?php

declare(strict_types=1);

namespace App\Handler\Library\Subscribers\Factory;

use App\DataProvider\LibrarySubscriberDataProvider;
use App\Handler\Library\Subscribers\PatchHandler;
use App\InputFilter\LibrarySubscriberPatchInputFilter;
use Laminas\InputFilter\InputFilterPluginManager;
use Library\Contract\UpdateSubscriberInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PatchHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(LibrarySubscriberPatchInputFilter::class);
        $updater       = $container->get(UpdateSubscriberInterface::class);
        $provider      = $container->get(LibrarySubscriberDataProvider::class);

        return new PatchHandler($inputFilter, $updater, $provider);
    }
}
