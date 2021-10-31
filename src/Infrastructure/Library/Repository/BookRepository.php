<?php

declare(strict_types=1);

namespace Infrastructure\Library\Repository;

use Infrastructure\Contract\AbstractRepository;
use Infrastructure\Contract\DatabaseConnectionInterface;
use Library\Book;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;
use Psr\Log\LoggerInterface;

class BookRepository extends AbstractRepository implements BookRepositoryInterface
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
        protected DatabaseConnectionInterface $connection,
        protected BookBorrowRegistryRepositoryInterface $bookBorrowRegistryRepository,
        protected LoggerInterface $logger
    ) {
        parent::__construct($this->connection, $this->logger);
    }

    public function registerNewBook(BookInterface $book): void
    {
        $this->insert(self::$metadata, $book);
    }

    public function unregisterBook(BookInterface $book): void
    {
        $this->delete(self::$metadata, $book);
    }

    public function getLibraryCatalog(): array
    {
        $books = $this->select(self::$metadata, ['title' => 'ASC']);
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
        $book       = $this->selectSingleWhere(self::$metadata, $conditions);
        if ($book) {
            $book = $this->addSubscriberToBook($book);
        }

        return $book;
    }

    public function addSubscriberToBook(array $book): array
    {
        $conditions         = [
            [
                'condition'  => 'book = :book_uuid',
                'parameters' => [
                    'book_uuid' => $book['uuid'],
                ],
            ],
        ];
        $book['subscriber'] = $this->bookBorrowRegistryRepository
            ->selectWhere(BookBorrowRegistryRepository::$metadata, $conditions);

        return $book;
    }

    public function updateBook(BookInterface $book): void
    {
        $this->update(self::$metadata, $book);
    }
}
