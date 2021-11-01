<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use Infrastructure\Contract\ResourceMetadataColumnInterface;

use function array_key_exists;
use function sprintf;
use function ucfirst;

class ResourceMetadataColumn implements ResourceMetadataColumnInterface
{
    private string $property;
    private string $type;
    private array $options;

    public function __construct(
        private string $name,
        private array $rawDefinition
    ) {
        $this->load();
    }

    private function load(): void
    {
        $this->property = $this->rawDefinition['property'] ?? $this->name;
        $this->type     = $this->rawDefinition['definition']['type'] ?? 'string';
        $this->options  = $this->rawDefinition['definition']['options'] ?? [];
    }

    public function getResourcePropertyAccessorName(): string
    {
        return $this->rawDefinition['definition']['property_accessor'] ?? sprintf('get%s', ucfirst($this->property));
    }

    public function isNullable(): bool
    {
        return array_key_exists('null', $this->options) && $this->options['null'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRawDefinition(): array
    {
        return $this->rawDefinition;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
