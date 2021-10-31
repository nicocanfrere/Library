<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\AbstractRepository;
use Library\BookBorrowRegistry;
use Library\Contract\BookBorrowRegistryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;

class BookBorrowRegistryRepository extends AbstractRepository implements BookBorrowRegistryRepositoryInterface
{
    public static array $metadata = [
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
                'indexes'    => [['columns' => ['book'], 'options' => ['unique' => true, 'name' => 'idx_book_uuid']]],
            ],
        ],
        'foreign_keys' => [
            ['column' => 'subscriber', 'table' => 'library_subscribers', 'ref' => 'uuid', 'options' => ['delete' => 'CASCADE', 'update' => 'NO_ACTION']],
            ['column' => 'book', 'table' => 'books', 'ref' => 'uuid', 'options' => ['delete' => 'CASCADE', 'update' => 'NO_ACTION']],
        ],
    ];

    public function borrowABook(BookBorrowRegistryInterface $registry): void
    {
        $this->insert(self::$metadata, $registry);
    }

    public function returnABook(string $subscriberUuid, string $bookUuid): void
    {
        // TODO: Implement returnABook() method.
    }

    public function bookCanBeBorrowed(string $bookUuid): bool
    {
        $conditions = [
            [
                'condition'  => 'book = :book',
                'parameters' => [
                    'book' => $bookUuid,
                ],
            ],
        ];
        $row        = $this->selectSingleWhere(self::$metadata, $conditions);

        return empty($row);
    }
}
