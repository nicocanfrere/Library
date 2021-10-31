<?php

declare(strict_types=1);

namespace App\Handler\Library\Books\Factory;

use App\DataProvider\BookDataProvider;
use App\Handler\Library\Books\PatchHandler;
use App\InputFilter\BookPatchInputFilter;
use Laminas\InputFilter\InputFilterPluginManager;
use Library\Contract\UpdateBookInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PatchHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(BookPatchInputFilter::class);
        $updater       = $container->get(UpdateBookInterface::class);
        $provider      = $container->get(BookDataProvider::class);

        return new PatchHandler($inputFilter, $updater, $provider);
    }
}
