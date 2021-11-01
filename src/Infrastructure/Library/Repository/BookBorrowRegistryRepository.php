<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Infrastructure\Contract\ResourceMetadataInterface;
use Library\BookBorrowRegistry;
use Library\Contract\BookBorrowRegistryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;

class BookBorrowRegistryRepository implements BookBorrowRegistryRepositoryInterface
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

    public function __construct(
        private QueryInterface $query,
        private ResourceMetadataInterface $resourceMetadata
    ) {
    }

    public function borrowABook(BookBorrowRegistryInterface $registry): void
    {
        $this->query->insert($this->resourceMetadata, $registry);
    }

    public function returnABook(BookBorrowRegistryInterface $registry): void
    {
        $this->query->delete($this->resourceMetadata, $registry);
    }

    public function bookCanBeBorrowed(string $bookUuid): bool
    {
        $row = $this->findOneByBookUuid($bookUuid);

        return empty($row);
    }

    public function findOneByBookUuid(string $bookUuid): array
    {
        $conditions = [
            [
                'condition'  => 'book = :book',
                'parameters' => [
                    'book' => $bookUuid,
                ],
            ],
        ];
        return $this->query->selectSingleWhere($this->resourceMetadata, $conditions);
    }

    public function findAllBySubscriberUuid(string $uuid): array
    {
        $conditions = [
            [
                'condition'  => 'subscriber = :subscriber_uuid',
                'parameters' => [
                    'subscriber_uuid' => $uuid,
                ],
            ],
        ];

        return $this->query->selectWhere($this->resourceMetadata, $conditions);
    }
}
