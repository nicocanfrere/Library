<?php

declare(strict_types=1);

namespace Library\UseCase;

use Library\Contract\BookFactoryInterface;
use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\RemoveBookInterface;
use Library\Exception\BookNotFoundException;
use Psr\Log\LoggerInterface;

class RemoveBook implements RemoveBookInterface
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private BookFactoryInterface $bookFactory,
        private LoggerInterface $logger
    ) {
    }

    public function remove(string $uuid): BookInterface
    {
        $book = $this->bookRepository->findBookByUuid($uuid);
        if (! $book) {
            $exception = new BookNotFoundException();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            throw $exception;
        }
        $book = $this->bookFactory->create($book);
        $this->bookRepository->unregisterBook($book);

        return $book;
    }
}
