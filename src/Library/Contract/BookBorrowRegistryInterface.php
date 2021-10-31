<?php

declare(strict_types=1);

namespace Library\Contract;

interface BookBorrowRegistryInterface
{
    public function __construct();

    public static function create(
        string $uuid,
        string $subscriber,
        string $book
    ): BookBorrowRegistryInterface;

    public function getUuid(): ?string;

    public function getSubscriber(): ?string;

    public function getBook(): ?string;
}
