<?php

declare(strict_types=1);

namespace Library\Contract;

interface BookBorrowRegistryRepositoryInterface
{
    public function borrowABook(string $subscriberUuid, string $bookUuid): void;

    public function returnABook(string $subscriberUuid, string $bookUuid): void;
}
