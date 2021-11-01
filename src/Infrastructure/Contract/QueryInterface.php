<?php

declare(strict_types=1);

namespace Infrastructure\Contract;

interface QueryInterface
{
    public function insert(array $metadata, mixed $resource): void;

    public function update(array $metadata, mixed $resource): void;

    public function delete(array $metadata, mixed $resource): void;

    public function select(array $metadata, ?array $orderBy = []): array;

    public function selectWhere(array $metadata, array $conditions): array;

    public function selectSingleWhere(array $metadata, array $conditions): array;

    public function generateInsertSqlQuery(array $metadata): string;

    public function generateUpdateSqlQuery(array $metadata): string;

    public function generateDeleteSqlQuery(array $metadata): string;

    public function generateOrderByPart(array $metadata, array $orderBy): string;

    public function prepareDataForInsert(array $metadata, mixed $resource): array;
}
