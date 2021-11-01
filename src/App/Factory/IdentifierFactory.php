<?php

declare(strict_types=1);

namespace App\Factory;

use Library\Contract\IdentifierFactoryInterface;
use Ramsey\Uuid\Uuid;

class IdentifierFactory implements IdentifierFactoryInterface
{
    public function create(): string
    {
        return Uuid::uuid4()->toString();
    }
}
