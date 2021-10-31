<?php

declare(strict_types=1);

namespace Library\Contract;

interface BookBorrowRegistryFactoryInterface
{
    public function create(array $data): BookBorrowRegistryInterface;
}
