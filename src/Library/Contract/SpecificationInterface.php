<?php

declare(strict_types=1);

namespace Library\Contract;

interface SpecificationInterface
{
    public function isSatisfiedBy($mixed): bool;
}
