<?php

declare(strict_types=1);

namespace Library\Contract;

interface BookFactoryInterface
{
    public function create(array $data): BookInterface;
}
