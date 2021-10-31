<?php

declare(strict_types=1);

namespace Library;

use JsonSerializable;
use Library\Contract\BookInterface;

class Book implements BookInterface, JsonSerializable
{
    private ?string $uuid           = null;
    private ?string $title          = null;
    private ?string $authorName     = null;
    private ?int $yearOfPublication = null;

    public function __construct()
    {
    }

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

    public function toArray(): array
    {
        return [
            'uuid'                => $this->uuid,
            'title'               => $this->title,
            'author_name'         => $this->authorName,
            'year_of_publication' => $this->yearOfPublication,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
