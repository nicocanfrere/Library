<?php

declare(strict_types=1);

namespace Library;

use Library\Contract\BookBorrowRegistryFactoryInterface;
use Library\Contract\BookBorrowRegistryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\IdentifierFactoryInterface;
use Library\Exception\BookAlreadyBorrowedException;
use Psr\Log\LoggerInterface;

class BookBorrowRegistryFactory implements BookBorrowRegistryFactoryInterface
{
    public function __construct(
        private BookBorrowRegistryRepositoryInterface $repository,
        private IdentifierFactoryInterface $identifierFactory,
        private LoggerInterface $logger
    ) {
    }

    public function create(array $data): BookBorrowRegistryInterface
    {
        $canBeBorrowed = $this->repository->bookCanBeBorrowed($data['book_uuid']);
        if (! $canBeBorrowed) {
            $exception = new BookAlreadyBorrowedException();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            throw $exception;
        }
        $uuid     = ! empty($data['uuid']) ? $data['uuid'] : $this->identifierFactory->create();
        $registry = BookBorrowRegistry::create(
            $uuid,
            $data['subscriber_uuid'],
            $data['book_uuid']
        );
        if (empty($data['uuid'])) {
            $this->repository->borrowABook($registry);
        }

        return $registry;
    }
}
