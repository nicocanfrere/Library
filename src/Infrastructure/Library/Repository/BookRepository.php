<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\QueryInterface;
use Infrastructure\Contract\ResourceMetadataInterface;
use Library\Book;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public static array $metadata = [
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
    ];

    public function __construct(
        private QueryInterface $query,
        private ResourceMetadataInterface $resourceMetadata,
        private BookBorrowRegistryRepositoryInterface $bookBorrowRegistryRepository
    ) {
    }

    public function registerNewBook(BookInterface $book): void
    {
        $this->query->insert($this->resourceMetadata, $book);
    }

    public function unregisterBook(BookInterface $book): void
    {
        $this->query->delete($this->resourceMetadata, $book);
    }

    public function getLibraryCatalog(): array
    {
        $books = $this->query->select($this->resourceMetadata, ['title' => 'ASC']);
        foreach ($books as $key => $book) {
            $books[$key] = $this->addSubscriberToBook($book);
        }

        return $books;
    }

    public function findBookByUuid(string $uuid): ?array
    {
        $conditions = [
            [
                'condition'  => 'uuid = :uuid',
                'parameters' => [
                    'uuid' => $uuid,
                ],
            ],
        ];
        $book       = $this->query->selectSingleWhere($this->resourceMetadata, $conditions);
        if ($book) {
            $book = $this->addSubscriberToBook($book);
        }

        return $book;
    }

    public function addSubscriberToBook(array $book): array
    {
        $row                = $this->bookBorrowRegistryRepository->findOneByBookUuid($book['uuid']);
        $book['subscriber'] = $row ? [$row] : [];

        return $book;
    }

    public function updateBook(BookInterface $book): void
    {
        $this->query->update($this->resourceMetadata, $book);
    }
}
