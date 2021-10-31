<?php

declare(strict_types=1);

namespace Library\Contract;

interface RemoveBookInterface
{
    public function remove(string $uuid): BookInterface;
}
