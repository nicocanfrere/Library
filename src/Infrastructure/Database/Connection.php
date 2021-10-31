<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Exception;
use Infrastructure\Contract\DatabaseConnectionInterface;
use PDO;
use Psr\Log\LoggerInterface;

class Connection implements DatabaseConnectionInterface
{
    private PDO $pdo;
    private bool $connected = false;

    public function __construct(
        private string $dsn,
        private string $username,
        private string $password,
        private array $options = [],
        private ?LoggerInterface $logger = null
    ) {
    }

    public function connect(): PDO
    {
        if ($this->connected) {
            return $this->pdo;
        }
        try {
            $this->pdo       = new PDO($this->dsn, $this->username, $this->password, $this->options);
            $this->connected = true;

            return $this->pdo;
        } catch (Exception $exception) {
            if ($this->logger) {
                $this->logger->critical(
                    __METHOD__,
                    ['error' => $exception->getMessage()]
                );
            }
            die($exception->getMessage());
        }
    }
}
