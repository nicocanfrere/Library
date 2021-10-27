<?php

declare(strict_types=1);

namespace Library;

use Library\Contract\BookInterface;

class Book implements BookInterface
{
    private ?string $uuid           = null;
    private ?string $title          = null;
    private ?string $authorName     = null;
    private ?int $yearOfPublication = null;

    public static function create(
        string $uuid,
        string $title,
        string $authorName,
        int $yearOfPublication
    ): BookInterface {
        $book                    = new static();
        $book->uuid              = $uuid;
        $book->title             = $title;
        $book->authorName        = $authorName;
        $book->yearOfPublication = $yearOfPublication;

        return $book;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function getYearOfPublication(): ?int
    {
        return $this->yearOfPublication;
    }
}
