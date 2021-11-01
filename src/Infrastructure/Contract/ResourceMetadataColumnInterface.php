<?php

declare(strict_types=1);

namespace Infrastructure\Contract;

interface ResourceMetadataColumnInterface
{
    public function getOptions(): array;

    public function isNullable(): bool;

    public function getRawDefinition(): array;

    public function getProperty(): string;

    public function getType(): string;

    public function getName(): string;

    public function getResourcePropertyAccessorName(): string;
}
