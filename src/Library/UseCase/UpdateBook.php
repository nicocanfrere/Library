<?php

declare(strict_types=1);

namespace Library\UseCase;

use Library\Book;
use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\UpdateBookInterface;
use Library\Exception\BookNotFoundException;

use function array_filter;
use function array_merge;

class UpdateBook implements UpdateBookInterface
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }

    public function update(string $uuid, array $data): BookInterface
    {
        $book = $this->bookRepository->findBookByUuid($uuid);
        if (! $book) {
            throw new BookNotFoundException();
        }
        unset($data['uuid']);
        $filtered = array_filter($data);
        $book     = array_merge($book, $filtered);
        $book     = Book::create(
            $book['uuid'],
            $book['title'],
            $book['author_name'],
            $book['year_of_publication']
        );

        $this->bookRepository->updateBook($book);

        return $book;
    }
}
