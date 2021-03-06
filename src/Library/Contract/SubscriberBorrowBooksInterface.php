<?php

declare(strict_types=1);

namespace Library\Contract;

interface SubscriberBorrowBooksInterface
{
    public const BORROWED_BOOKS       = 'borrowed_books';
    public const NOT_BORROWABLE_BOOKS = 'not_borrowable_books';
    public const UNKNOWN_BOOKS        = 'unknown_books';

    public function borrow(string $subscriberUuid, array $bookUuids): array;
}
