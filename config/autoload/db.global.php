<?php

declare(strict_types=1);

return [
    'db' => [
        'dsn'      => '',
        'username' => '',
        'password' => '',
        'options'  => [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAME 'UTF8'",
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        ],
    ],
];
