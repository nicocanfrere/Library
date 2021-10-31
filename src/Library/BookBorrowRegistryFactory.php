<?php

declare(strict_types=1);

namespace Library;

use Library\Contract\BookBorrowRegistryFactoryInterface;
use Library\Contract\BookBorrowRegistryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Exception\BookAlreadyBorrowedException;
use Ramsey\Uuid\Uuid;

class BookBorrowRegistryFactory implements BookBorrowRegistryFactoryInterface
{
    public function __construct(
        private BookBorrowRegistryRepositoryInterface $repository
    ) {
    }

    public function create(array $data): BookBorrowRegistryInterface
    {
        $canBeBorrowed = $this->repository->bookCanBeBorrowed($data['book_uuid']);
        if (! $canBeBorrowed) {
            throw new BookAlreadyBorrowedException();
        }
        $uuid     = !empty($data['uuid']) ? $data['uuid'] : Uuid::uuid4()->toString();
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
