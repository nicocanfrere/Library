<?php

declare(strict_types=1);

namespace Library\Contract;

interface BookInterface
{
    public function __construct();

    public static function create(
        string $uuid,
        string $title,
        string $authorName,
        int $yearOfPublication
    ): BookInterface;

    public function getUuid(): ?string;

    public function getTitle(): ?string;

    public function getYearOfPublication(): ?int;

    public function getAuthorName(): ?string;

    public function toArray(): array;
}
