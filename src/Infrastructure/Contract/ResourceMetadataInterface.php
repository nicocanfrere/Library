<?php

declare(strict_types=1);

namespace Infrastructure\Contract;

interface ResourceMetadataInterface
{
    public function toSnakeCase(string $string): string;

    public function getColumnNames(): array;

    public function getPrimaryKeyName(): string;

    public function getTableName(): string;

    public function loadMetadata(array $metadata): ResourceMetadataInterface;

    public function getClassName(): string;

    /**
     * @return ResourceMetadataInterface[]
     */
    public function getColumns(): array;

    public function getPrimaryKeyAccessor(): string;
}
