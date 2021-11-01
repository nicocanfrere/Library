<?php

declare(strict_types=1);

namespace Library\Contract;

interface BookBorrowRegistryRepositoryInterface
{
    public function borrowABook(BookBorrowRegistryInterface $registry): void;

    public function returnABook(BookBorrowRegistryInterface $registry): void;

    public function bookCanBeBorrowed(string $bookUuid): bool;

    public function findOneByBookUuid(string $bookUuid): array;

    public function findAllBySubscriberUuid(string $uuid): array;
}
