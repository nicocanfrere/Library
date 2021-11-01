<?php

declare(strict_types=1);

use Library\Book;
use Library\BookBorrowRegistry;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\LibrarySubscriber;

return [
    'db' => [
        'dsn'               => '',
        'username'          => '',
        'password'          => '',
        'options'           => [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAME 'UTF8'",
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ],
        'resource_metadata' => [
            BookRepositoryInterface::class
                => [
                    'class'   => Book::class,
                    'table'   => 'books',
                    'primary' => 'uuid',
                    'columns' => [
                        'uuid'                => [
                            'property'   => 'uuid',
                            'definition' => ['type' => 'uuid', 'options' => ['null' => false]],
                        ],
                        'title'               => [
                            'property'   => 'title',
                            'definition' => ['type' => 'string', 'options' => ['limit' => 255, 'null' => true]],
                        ],
                        'author_name'         => [
                            'property'   => 'authorName',
                            'definition' => ['type' => 'string', 'options' => ['limit' => 50, 'null' => true]],
                        ],
                        'year_of_publication' => [
                            'property'   => 'yearOfPublication',
                            'definition' => ['type' => 'integer', 'options' => ['null' => true]],
                        ],
                    ],
                ],
            LibrarySubscriberRepositoryInterface::class
                => [
                    'class'   => LibrarySubscriber::class,
                    'table'   => 'library_subscribers',
                    'primary' => 'uuid',
                    'columns' => [
                        'uuid'       => [
                            'property'   => 'uuid',
                            'definition' => ['type' => 'uuid', 'options' => ['null' => false]],
                        ],
                        'first_name' => [
                            'property'   => 'firstName',
                            'definition' => ['type' => 'string', 'options' => ['limit' => 100, 'null' => true]],
                        ],
                        'last_name'  => [
                            'property'   => 'lastName',
                            'definition' => ['type' => 'string', 'options' => ['limit' => 100, 'null' => true]],
                        ],
                        'email'      => [
                            'property'   => 'email',
                            'definition' => ['type' => 'string', 'options' => ['limit' => 255, 'null' => true]],
                        ],
                    ],
                ],
            BookBorrowRegistryRepositoryInterface::class
                => [
                    'class'        => BookBorrowRegistry::class,
                    'table'        => 'book_borrows',
                    'primary'      => 'uuid',
                    'columns'      => [
                        'uuid'       => [
                            'property'   => 'uuid',
                            'definition' => ['type' => 'uuid', 'options' => ['null' => false]],
                        ],
                        'subscriber' => [
                            'property'   => 'subscriber',
                            'definition' => ['type' => 'uuid', 'options' => ['null' => false]],
                        ],
                        'book'       => [
                            'property'   => 'book',
                            'definition' => ['type' => 'uuid', 'options' => ['null' => false]],
                            'indexes'    => [
                                [
                                    'columns' => ['book'],
                                    'options' => ['unique' => true, 'name' => 'idx_book_uuid'],
                                ],
                            ],
                        ],
                    ],
                    'foreign_keys' => [
                        [
                            'column'  => 'subscriber',
                            'table'   => 'library_subscribers',
                            'ref'     => 'uuid',
                            'options' => ['delete' => 'CASCADE', 'update' => 'NO_ACTION'],
                        ],
                        [
                            'column'  => 'book',
                            'table'   => 'books',
                            'ref'     => 'uuid',
                            'options' => ['delete' => 'CASCADE', 'update' => 'NO_ACTION'],
                        ],
                    ],
                ],
        ],
    ],
];
