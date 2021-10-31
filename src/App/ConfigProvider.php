<?php

declare(strict_types=1);

namespace App;

use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies'  => $this->getDependencies(),
            'input_filters' => $this->getInputFilters(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [],
            'factories'  => [
                Handler\IndexHandler::class
                => Handler\IndexHandlerFactory::class,
                Handler\Library\Books\DeleteHandler::class
                => Handler\Library\Books\Factory\DeleteHandlerFactory::class,
                Handler\Library\Books\ListHandler::class
                => Handler\Library\Books\Factory\ListHandlerFactory::class,
                Handler\Library\Books\PatchHandler::class
                => Handler\Library\Books\Factory\PatchHandlerFactory::class,
                Handler\Library\Books\PostHandler::class
                => Handler\Library\Books\Factory\PostHandlerFactory::class,
                Handler\Library\Books\PutHandler::class
                => Handler\Library\Books\Factory\PutHandlerFactory::class,
                Handler\Library\Books\SingleHandler::class
                => Handler\Library\Books\Factory\SingleHandlerFactory::class,
                Handler\Library\Subscribers\DeleteHandler::class
                => Handler\Library\Subscribers\Factory\DeleteHandlerFactory::class,
                Handler\Library\Subscribers\ListHandler::class
                => Handler\Library\Subscribers\Factory\ListHandlerFactory::class,
                Handler\Library\Subscribers\PatchHandler::class
                => Handler\Library\Subscribers\Factory\PatchHandlerFactory::class,
                Handler\Library\Subscribers\PostHandler::class
                => Handler\Library\Subscribers\Factory\PostHandlerFactory::class,
                Handler\Library\Subscribers\PutHandler::class
                => Handler\Library\Subscribers\Factory\PutHandlerFactory::class,
                Handler\Library\Subscribers\SingleHandler::class
                => Handler\Library\Subscribers\Factory\SingleHandlerFactory::class,
                DataProvider\LibrarySubscriberDataProvider::class
                => DataProvider\LibrarySubscriberDataProviderFactory::class,
                DataProvider\BookDataProvider::class
                => DataProvider\BookDataProviderFactory::class,
            ],
        ];
    }

    public function getInputFilters(): array
    {
        return [
            'factories' => [
                InputFilter\BookCreateInputFilter::class             => InvokableFactory::class,
                InputFilter\BookPatchInputFilter::class              => InvokableFactory::class,
                InputFilter\LibrarySubscriberPatchInputFilter::class => InvokableFactory::class,
                InputFilter\LibrarySubscriberInputFilter::class      => InvokableFactory::class,
            ],
        ];
    }
}
