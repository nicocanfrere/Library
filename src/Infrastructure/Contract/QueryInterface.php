<?php

declare(strict_types=1);

namespace Infrastructure\Contract;

interface QueryInterface
{
    public function insert(array|ResourceMetadataInterface $metadata, mixed $resource): void;

    public function update(array|ResourceMetadataInterface $metadata, mixed $resource): void;

    public function delete(array|ResourceMetadataInterface $metadata, mixed $resource): void;

    public function select(array|ResourceMetadataInterface $metadata, ?array $orderBy = []): array;

    public function selectWhere(array|ResourceMetadataInterface $metadata, array $conditions): array;

    public function selectSingleWhere(array|ResourceMetadataInterface $metadata, array $conditions): array;

    public function generateInsertSqlQuery(array|ResourceMetadataInterface $metadata): string;

    public function generateUpdateSqlQuery(array|ResourceMetadataInterface $metadata): string;

    public function generateDeleteSqlQuery(array|ResourceMetadataInterface $metadata): string;

    public function generateOrderByPart(array|ResourceMetadataInterface $metadata, array $orderBy): string;

    public function prepareDataForInsert(array|ResourceMetadataInterface $metadata, mixed $resource): array;
}
