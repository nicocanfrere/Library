<?php

declare(strict_types=1);

namespace Library\Contract;

interface UpdateSubscriberInterface
{
    public function update(string $uuid, array $data): LibrarySubscriberInterface;
}
