<?php

declare(strict_types=1);

namespace Library\Contract;

interface RemoveSubscriberInterface
{
    public function remove(string $uuid): LibrarySubscriberInterface;
}
