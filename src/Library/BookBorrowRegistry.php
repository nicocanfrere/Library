<?php

declare(strict_types=1);

namespace Library;

use Library\Contract\BookBorrowRegistryInterface;

class BookBorrowRegistry implements BookBorrowRegistryInterface
{
    private ?string $uuid       = null;
    private ?string $subscriber = null;
    private ?string $book       = null;

    public function __construct()
    {
    }

    public static function create(
        string $uuid,
        string $subscriber,
        string $book
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

    public function getSubscriber(): ?string
    {
        return $this->subscriber;
    }

    public function getBook(): ?string
    {
        return $this->book;
    }
}
