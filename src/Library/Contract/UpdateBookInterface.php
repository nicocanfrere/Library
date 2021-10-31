<?php

declare(strict_types=1);

namespace Library\Contract;

interface UpdateBookInterface
{
    public function update(string $uuid, array $data): BookInterface;
}
