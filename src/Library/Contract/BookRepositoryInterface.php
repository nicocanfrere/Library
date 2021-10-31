<?php

declare(strict_types=1);

namespace Library\Contract;

interface BookRepositoryInterface
{
    public function registerNewBook(BookInterface $book): void;

    public function updateBook(BookInterface $book): void;

    public function unregisterBook(BookInterface $book): void;

    public function getLibraryCatalog(): array;

    public function findBookByUuid(string $uuid): ?array;
}
