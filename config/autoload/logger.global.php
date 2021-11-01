<?php

declare(strict_types=1);

return [
    'logger' => [
        'name'     => 'monolog',
        'handlers' => [
            'stream_handler' => [
                'path' => realpath(__DIR__ . '/../../data/logs').'/app.log',
            ],
        ],
    ],
];
