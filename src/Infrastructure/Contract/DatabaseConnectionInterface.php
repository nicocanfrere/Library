<?php

declare(strict_types=1);

namespace Infrastructure\Contract;

use PDO;

/**
 * Interface DatabaseConnectionInterface
 */
interface DatabaseConnectionInterface
{
    public function connect(): PDO;
}
