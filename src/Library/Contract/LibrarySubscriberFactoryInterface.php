<?php

declare(strict_types=1);

namespace Library\Contract;

interface LibrarySubscriberFactoryInterface
{
    public function create(array $data, ?bool $save = true): LibrarySubscriberInterface;
}
