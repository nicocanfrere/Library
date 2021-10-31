<?php

declare(strict_types=1);

namespace Library\Contract;

interface BookBorrowRegistryInterface
{
    public function __construct();

    public static function create(
        string $uuid,
        LibrarySubscriberInterface $subscriber,
        BookInterface $book
    ): BookBorrowRegistryInterface;

    public function getUuid(): ?string;

    public function getSubscriber(): ?LibrarySubscriberInterface;

    public function getBook(): ?BookInterface;
}
