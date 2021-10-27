<?php

declare(strict_types=1);

namespace Infrastructure\Contract;

/**
 * Interface DatabaseConnectionInterface
 */
interface DatabaseConnectionInterface
{
    public function connect();
}
