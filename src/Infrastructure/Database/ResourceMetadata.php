<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Exception;
use Infrastructure\Contract\ResourceMetadataColumnInterface;
use Infrastructure\Contract\ResourceMetadataInterface;

use function array_keys;
use function preg_replace;
use function sprintf;
use function strrpos;
use function strtolower;
use function substr;
use function ucfirst;

class ResourceMetadata implements ResourceMetadataInterface
{
    private string $className;
    private string $tableName;
    private string $primaryKeyName = 'id';
    /** @var ResourceMetadataColumnInterface[] */
    private array $columns = [];

    public function loadMetadata(array $metadata): self
    {
        if (empty($metadata['class'])) {
            throw new Exception('The FQCN of the resource is missing!');
        }
        $this->className = $metadata['class'];
        if (empty($metadata['columns'])) {
            throw new Exception(
                sprintf(
                    'There is no columns definitions for the resource %s !',
                    $this->className
                )
            );
        }
        $this->tableName      = $metadata['table'] ?? $this->toSnakeCase(
            substr(
                $this->className,
                strrpos(
                    $this->className,
                    '\\'
                ) + 1
            )
        );
        $this->primaryKeyName = $metadata['primary'] ?? $this->primaryKeyName;
        foreach ($metadata['columns'] as $name => $definition) {
            $this->columns[$name] = new ResourceMetadataColumn($name, $definition);
        }

        return $this;
    }

    public function toSnakeCase(string $string): string
    {
        $string = preg_replace(
            '/(?<!^)[A-Z]/',
            '_$0',
            $string
        );
        return $string ? strtolower($string) : '';
    }

    /**
     * @return ResourceMetadataColumnInterface[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getPrimaryKeyName(): string
    {
        return $this->primaryKeyName;
    }

    public function getColumnNames(): array
    {
        return array_keys($this->columns);
    }

    public function getPrimaryKeyAccessor(): string
    {
        return sprintf('get%s', ucfirst($this->primaryKeyName));
    }
}
