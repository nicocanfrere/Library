<?php

declare(strict_types=1);

namespace Library\UseCase;

use Library\Contract\BookFactoryInterface;
use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\RemoveBookInterface;
use Library\Exception\BookNotFoundException;

class RemoveBook implements RemoveBookInterface
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private BookFactoryInterface $bookFactory
    ) {
    }

    public function remove(string $uuid): BookInterface
    {
        $book = $this->bookRepository->findBookByUuid($uuid);
        if (! $book) {
            throw new BookNotFoundException();
        }
        $book = $this->bookFactory->create($book);
        $this->bookRepository->unregisterBook($book);

        return $book;
    }
}
