<?php

declare(strict_types=1);

namespace Infrastructure\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases'   => [
                'stream_handler'       => StreamHandler::class,
                LoggerInterface::class => Logger::class,
            ],
            'factories' => [
                Logger::class        => LoggerFactory::class,
                StreamHandler::class => StreamHandlerFactory::class,
            ],
        ];
    }
}
