<?php

declare(strict_types=1);

namespace Library;

use Library\Contract\BookFactoryInterface;
use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\IdentifierFactoryInterface;

class BookFactory implements BookFactoryInterface
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    public function create(array $data): BookInterface
    {
        $uuid = ! empty($data['uuid']) ? $data['uuid'] : $this->identifierFactory->create();
        $book = Book::create(
            $uuid,
            $data['title'],
            $data['author_name'],
            $data['year_of_publication']
        );

        if (empty($data['uuid'])) {
            $this->bookRepository->registerNewBook($book);
        }

        return $book;
    }
}
