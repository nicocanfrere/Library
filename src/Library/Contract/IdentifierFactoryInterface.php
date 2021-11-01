<?php

declare(strict_types=1);

namespace Library\Contract;

interface IdentifierFactoryInterface
{
    public function create(): string;
}
