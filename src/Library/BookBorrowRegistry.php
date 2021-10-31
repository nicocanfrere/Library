<?php

declare(strict_types=1);

namespace Library;

use Library\Contract\BookBorrowRegistryInterface;
use Library\Contract\BookInterface;
use Library\Contract\LibrarySubscriberInterface;

class BookBorrowRegistry implements BookBorrowRegistryInterface
{
    private ?string $uuid                           = null;
    private ?LibrarySubscriberInterface $subscriber = null;
    private ?BookInterface $book                    = null;

    public function __construct()
    {
    }

    public static function create(
        string $uuid,
        LibrarySubscriberInterface $subscriber,
        BookInterface $book
    ): BookBorrowRegistryInterface {
        $registry             = new static();
        $registry->uuid       = $uuid;
        $registry->subscriber = $subscriber;
        $registry->book       = $book;

        return $registry;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getSubscriber(): ?LibrarySubscriberInterface
    {
        return $this->subscriber;
    }

    public function getBook(): ?BookInterface
    {
        return $this->book;
    }
}
