<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Infrastructure\Contract\QueryInterface;
use Psr\Log\LoggerInterface;

use function array_keys;
use function array_map;
use function array_merge;
use function count;
use function implode;
use function in_array;
use function method_exists;
use function sprintf;
use function strtoupper;
use function trim;
use function ucfirst;

class Query implements QueryInterface
{
    public function __construct(
        protected DatabaseConnectionInterface $connection,
        protected LoggerInterface $logger
    ) {
    }

    public function insert(array $metadata, mixed $resource): void
    {
        $pdo = $this->connection->connect();
        try {
            $sql  = $this->generateInsertSqlQuery($metadata);
            $data = $this->prepareDataForInsert($metadata, $resource);
            $stmt = $pdo->prepare($sql);
            $pdo->beginTransaction();
            $success = $stmt->execute($data);
            $pdo->commit();
            if (false === $success) {
                $this->logger->critical(
                    __METHOD__,
                    ['error_info' => $stmt->errorInfo(), 'error_code' => $stmt->errorCode()]
                );
            }
        } catch (PDOException $exception) {
            $pdo->rollBack();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
        }
    }

    public function update(array $metadata, mixed $resource): void
    {
        $pdo = $this->connection->connect();
        try {
            $sql  = $this->generateUpdateSqlQuery($metadata);
            $data = $this->prepareDataForInsert($metadata, $resource);
            $stmt = $pdo->prepare($sql);
            $pdo->beginTransaction();
            $success = $stmt->execute($data);
            $pdo->commit();
            if (false === $success) {
                $this->logger->critical(
                    __METHOD__,
                    ['error_info' => $stmt->errorInfo(), 'error_code' => $stmt->errorCode()]
                );
            }
        } catch (Exception $exception) {
            $pdo->rollBack();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
        }
    }

    public function delete(array $metadata, mixed $resource): void
    {
        $pdo = $this->connection->connect();
        try {
            $method = sprintf('get%s', ucfirst($metadata['primary']));
            if (! method_exists($metadata['class'], $method)) {
                throw new RuntimeException(
                    sprintf('Method %s does not exists in %s class', $method, $metadata['class'])
                );
            }
            $sql  = $this->generateDeleteSqlQuery($metadata);
            $stmt = $pdo->prepare($sql);
            $pdo->beginTransaction();
            $success = $stmt->execute([$metadata['primary'] => $resource->$method()]);
            $pdo->commit();
            if (false === $success) {
                $this->logger->critical(
                    __METHOD__,
                    ['error_info' => $stmt->errorInfo(), 'error_code' => $stmt->errorCode()]
                );
            }
        } catch (PDOException $exception) {
            $pdo->rollBack();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
        }
    }

    public function select(array $metadata, ?array $orderBy = []): array
    {
        $pdo = $this->connection->connect();
        try {
            $sql     = trim(
                sprintf(
                    'SELECT * FROM %s %s',
                    $metadata['table'],
                    $orderBy ? $this->generateOrderByPart($metadata, $orderBy) : ''
                )
            );
            $stmt    = $pdo->prepare($sql);
            $success = $stmt->execute();
            if (false === $success) {
                $this->logger->critical(
                    __METHOD__,
                    ['error_info' => $stmt->errorInfo(), 'error_code' => $stmt->errorCode()]
                );
                throw new Exception();
            }

            return $stmt->fetchAll();
        } catch (Exception $exception) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );

            return [];
        }
    }

    public function selectWhere(array $metadata, array $conditions): array
    {
        $pdo = $this->connection->connect();
        try {
            $conditionsStr = [];
            $parameters    = [];
            foreach ($conditions as $condition) {
                $conditionsStr[] = $condition['condition'];
                $parameters      = array_merge($condition['parameters']);
            }
            $conditionsStr = trim(implode(' ', $conditionsStr));
            $sql           = trim(
                sprintf(
                    'SELECT * FROM %s WHERE %s',
                    $metadata['table'],
                    $conditionsStr
                )
            );
            $stmt          = $pdo->prepare($sql);
            $success       = $stmt->execute($parameters);
            if (false === $success) {
                $this->logger->critical(
                    __METHOD__,
                    ['error_info' => $stmt->errorInfo(), 'error_code' => $stmt->errorCode()]
                );
                throw new Exception();
            }

            return $stmt->fetchAll();
        } catch (Exception $exception) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );

            return [];
        }
    }

    public function selectSingleWhere(array $metadata, array $conditions): array
    {
        $pdo = $this->connection->connect();
        try {
            $conditionsStr = [];
            $parameters    = [];
            foreach ($conditions as $condition) {
                $conditionsStr[] = $condition['condition'];
                $parameters      = array_merge($condition['parameters']);
            }
            $conditionsStr = trim(implode(' ', $conditionsStr));
            $sql           = trim(
                sprintf(
                    'SELECT * FROM %s WHERE %s',
                    $metadata['table'],
                    $conditionsStr
                )
            );
            $stmt          = $pdo->prepare($sql);
            $success       = $stmt->execute($parameters);
            if (false === $success) {
                $this->logger->critical(
                    __METHOD__,
                    ['error_info' => $stmt->errorInfo(), 'error_code' => $stmt->errorCode()]
                );
                throw new Exception();
            }
            $results = $stmt->fetchAll();
            if (count($results) > 1) {
                $exception = new Exception('More than one result');
                $this->logger->critical(
                    __METHOD__,
                    ['error' => $exception->getMessage()]
                );
                throw $exception;
            }
            if (count($results) === 0) {
                return [];
            }

            return $results[0];
        } catch (Exception $exception) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );

            return [];
        }
    }

    public function generateInsertSqlQuery(array $metadata): string
    {
        $values          = array_keys($metadata['columns']);
        $columns         = trim(implode(', ', $values));
        $parameters      = array_map(
            function ($param) {
                return sprintf(':%s', $param);
            },
            $values
        );
        $namedParameters = trim(implode(', ', $parameters));

        return sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $metadata['table'],
            $columns,
            $namedParameters
        );
    }

    public function generateUpdateSqlQuery(array $metadata): string
    {
        $setClause = [];
        foreach (array_keys($metadata['columns']) as $column) {
            if ($column === $metadata['primary']) {
                continue;
            }
            $setClause[] = sprintf(
                '%s = :%s',
                $column,
                $column
            );
        }
        $setClause   = trim(implode(', ', $setClause));
        $whereClause = sprintf(
            '%s = :%s',
            $metadata['primary'],
            $metadata['primary']
        );
        return sprintf(
            'UPDATE %s SET %s WHERE %s',
            $metadata['table'],
            $setClause,
            $whereClause
        );
    }

    public function generateDeleteSqlQuery(array $metadata): string
    {
        return sprintf(
            'DELETE FROM %s WHERE uuid = :%s',
            $metadata['table'],
            $metadata['primary']
        );
    }

    public function generateOrderByPart(array $metadata, array $orderBy): string
    {
        $orderByStr = [];
        $columns    = array_keys($metadata['columns']);
        foreach ($orderBy as $column => $order) {
            $order = strtoupper($order);
            if (
                ! in_array($column, $columns)
                || ! in_array($order, ['ASC', 'DESC'])
            ) {
                continue;
            }
            $orderByStr[] = sprintf(
                '%s %s',
                $column,
                $order
            );
        }

        return sprintf(
            'ORDER BY %s',
            trim(implode(', ', $orderByStr))
        );
    }

    public function prepareDataForInsert(array $metadata, mixed $resource): array
    {
        $data = [];
        foreach ($metadata['columns'] as $name => $meta) {
            $method = sprintf('get%s', ucfirst($meta['property']));
            if (! method_exists($metadata['class'], $method)) {
                throw new RuntimeException(
                    sprintf('Method %s does not exists in %s class', $method, $metadata['class'])
                );
            }
            $data[$name] = $resource->$method();
        }

        return $data;
    }
}
