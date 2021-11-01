<?php

declare(strict_types=1);

namespace InfrastructureTest;

use Exception;
use Infrastructure\Contract\ResourceMetadataInterface;
use Infrastructure\Database\ResourceMetadata;
use PHPUnit\Framework\TestCase;

class ResourceMetadataTest extends TestCase
{
    private ResourceMetadataInterface $resourceMetadata;

    protected function setUp(): void
    {
        $this->resourceMetadata = new ResourceMetadata();
    }

    /**
     * @test
     * @dataProvider camelCaseWords
     */
    public function toSnakeCase(string $word, string $expected)
    {
        $snakeCase = $this->resourceMetadata->toSnakeCase($word);
        $this->assertEquals($expected, $snakeCase);
    }

    public function camelCaseWords(): array
    {
        return [
            ['azerty', 'azerty'],
            ['Azerty', 'azerty'],
            ['AzertyUiop', 'azerty_uiop'],
            ['azertyUiop', 'azerty_uiop'],
            ['MyResource', 'my_resource'],
        ];
    }

    /**
     * @test
     */
    public function throwExceptionOnClassNameMissing()
    {
        $metadata = [];
        $this->expectException(Exception::class);
        $this->resourceMetadata->loadMetadata($metadata);
    }

    /**
     * @test
     */
    public function throwExceptionOnColumnsDefinitionsMissing()
    {
        $metadata = ['class' => 'App\MyResource'];
        $this->expectException(Exception::class);
        $this->resourceMetadata->loadMetadata($metadata);
    }

    /**
     * @test
     */
    public function setTableName()
    {
        $metadata = ['class' => 'App\MyResource', 'columns' => ['column1' => [true]]];
        $this->resourceMetadata->loadMetadata($metadata);
        $this->assertEquals($metadata['class'], $this->resourceMetadata->getClassName());
        $this->assertEquals('my_resource', $this->resourceMetadata->getTableName());
        $metadata = ['class' => 'App\MyResource', 'table' => 'resources', 'columns' => ['column1' => [true]]];
        $this->resourceMetadata->loadMetadata($metadata);
        $this->assertEquals($metadata['class'], $this->resourceMetadata->getClassName());
        $this->assertEquals($metadata['table'], $this->resourceMetadata->getTableName());
    }

    /**
     * @test
     */
    public function setPrimaryKeyName()
    {
        $metadata = ['class' => 'App\MyResource', 'columns' => ['column1' => [true]]];
        $this->resourceMetadata->loadMetadata($metadata);
        $this->assertEquals('id', $this->resourceMetadata->getPrimaryKeyName());
        $metadata = ['class' => 'App\MyResource', 'primary' => 'uuid', 'columns' => ['column1' => [true]]];
        $this->resourceMetadata->loadMetadata($metadata);
        $this->assertEquals($metadata['primary'], $this->resourceMetadata->getPrimaryKeyName());
    }
}
