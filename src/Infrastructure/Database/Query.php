<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Exception;
use Infrastructure\Contract\DatabaseConnectionInterface;
use Infrastructure\Contract\QueryInterface;
use Infrastructure\Contract\ResourceMetadataColumnInterface;
use Infrastructure\Contract\ResourceMetadataInterface;
use PDOException;
use Psr\Log\LoggerInterface;
use RuntimeException;

use function array_keys;
use function array_map;
use function array_merge;
use function count;
use function implode;
use function in_array;
use function is_array;
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

    public function insert(array|ResourceMetadataInterface $metadata, mixed $resource): void
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

    public function update(array|ResourceMetadataInterface $metadata, mixed $resource): void
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

    public function delete(array|ResourceMetadataInterface $metadata, mixed $resource): void
    {
        $pdo = $this->connection->connect();
        try {
            $method = $metadata instanceof ResourceMetadataInterface ?
                $metadata->getPrimaryKeyAccessor() :
                sprintf('get%s', ucfirst($metadata['primary']));
            $class  = $metadata instanceof ResourceMetadataInterface ?
                $metadata->getClassName() : $metadata['class'];
            if (! method_exists($class, $method)) {
                throw new RuntimeException(
                    sprintf('Method %s does not exists in %s class', $method, $class)
                );
            }
            $sql  = $this->generateDeleteSqlQuery($metadata);
            $stmt = $pdo->prepare($sql);
            $pdo->beginTransaction();
            $primary = $metadata instanceof ResourceMetadataInterface ?
                $metadata->getPrimaryKeyName() :
                $metadata['primary'];
            $success = $stmt->execute([$primary => $resource->$method()]);
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

    public function select(array|ResourceMetadataInterface $metadata, ?array $orderBy = []): array
    {
        $pdo = $this->connection->connect();
        try {
            $sql     = trim(
                sprintf(
                    'SELECT * FROM %s %s',
                    $metadata instanceof ResourceMetadataInterface ? $metadata->getTableName() : $metadata['table'],
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
            /** before PHP8, fetchAll return array|false, now always array */
            /** @phpstan-ignore-next-line */
            return $stmt->fetchAll();
        } catch (Exception $exception) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );

            return [];
        }
    }

    public function selectWhere(array|ResourceMetadataInterface $metadata, array $conditions): array
    {
        $pdo = $this->connection->connect();
        try {
            $conditionsStr = [];
            $parameters    = [];
            $table         = $metadata instanceof ResourceMetadataInterface ? $metadata->getTableName() : $metadata['table'];
            foreach ($conditions as $condition) {
                $conditionsStr[] = $condition['condition'];
                $parameters      = array_merge($condition['parameters']);
            }
            $conditionsStr = trim(implode(' ', $conditionsStr));
            $sql           = trim(
                sprintf(
                    'SELECT * FROM %s WHERE %s',
                    $table,
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
            /** before PHP8, fetchAll return array|false, now always array */
            /** @phpstan-ignore-next-line */
            return $stmt->fetchAll();
        } catch (Exception $exception) {
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );

            return [];
        }
    }

    public function selectSingleWhere(array|ResourceMetadataInterface $metadata, array $conditions): array
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
                    $metadata instanceof ResourceMetadataInterface ?
                        $metadata->getTableName() :
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
            /** @var array $results */
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

    public function generateInsertSqlQuery(array|ResourceMetadataInterface $metadata): string
    {
        $values          = $metadata instanceof ResourceMetadataInterface ?
            $metadata->getColumnNames() :
            array_keys($metadata['columns']);
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
            $metadata instanceof ResourceMetadataInterface ? $metadata->getTableName() : $metadata['table'],
            $columns,
            $namedParameters
        );
    }

    public function generateUpdateSqlQuery(array|ResourceMetadataInterface $metadata): string
    {
        $setClause = [];
        $columns   = $metadata instanceof ResourceMetadataInterface ?
            $metadata->getColumnNames() :
            array_keys($metadata['columns']);
        $primary   = $metadata instanceof ResourceMetadataInterface ?
            $metadata->getPrimaryKeyName() :
            $metadata['primary'];
        $table     = $metadata instanceof ResourceMetadataInterface ?
            $metadata->getTableName() :
            $metadata['table'];
        foreach ($columns as $column) {
            if ($column === $primary) {
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
            $primary,
            $primary
        );
        return sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            $setClause,
            $whereClause
        );
    }

    public function generateDeleteSqlQuery(array|ResourceMetadataInterface $metadata): string
    {
        return sprintf(
            'DELETE FROM %s WHERE %s = :%s',
            $metadata instanceof ResourceMetadataInterface ? $metadata->getTableName() : $metadata['table'],
            $metadata instanceof ResourceMetadataInterface ? $metadata->getPrimaryKeyName() : $metadata['primary'],
            $metadata instanceof ResourceMetadataInterface ? $metadata->getPrimaryKeyName() : $metadata['primary']
        );
    }

    public function generateOrderByPart(array|ResourceMetadataInterface $metadata, array $orderBy): string
    {
        $orderByStr = [];
        $columns    = $metadata instanceof ResourceMetadataInterface ? $metadata->getColumnNames() : array_keys($metadata['columns']);
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

    public function prepareDataForInsert(array|ResourceMetadataInterface $metadata, mixed $resource): array
    {
        $data = [];
        if (is_array($metadata)) {
            foreach ($metadata['columns'] as $name => $meta) {
                $method = sprintf('get%s', ucfirst($meta['property']));
                if (! method_exists($metadata['class'], $method)) {
                    throw new RuntimeException(
                        sprintf('Method %s does not exists in %s class', $method, $metadata['class'])
                    );
                }
                $data[$name] = $resource->$method();
            }
        }
        if ($metadata instanceof ResourceMetadataInterface) {
            foreach ($metadata->getColumns() as $name => $meta) {
                /** @var ResourceMetadataColumnInterface $meta */
                $method = $meta->getResourcePropertyAccessorName();
                if (! method_exists($metadata->getClassName(), $method)) {
                    throw new RuntimeException(
                        sprintf('Method %s does not exists in %s class', $method, $metadata->getClassName())
                    );
                }
                $data[$name] = $resource->$method();
            }
        }

        return $data;
    }
}
