<?php

declare(strict_types=1);

namespace InfrastructureTest;

use Infrastructure\Database\ResourceMetadataColumn;
use PHPUnit\Framework\TestCase;

class ResourceMetadataColumnTest extends TestCase
{
    /**
     * @test
     */
    public function bases()
    {
        $column = new ResourceMetadataColumn('test', []);
        $this->assertEquals('test', $column->getName());
        $this->assertEquals('test', $column->getProperty());
        $this->assertEquals('string', $column->getType());
        $this->assertFalse($column->isNullable());
    }

    /**
     * @test
     */
    public function property()
    {
        $column = new ResourceMetadataColumn('test', ['property' => 'name']);
        $this->assertEquals('name', $column->getProperty());
    }

    /**
     * @test
     */
    public function type()
    {
        $column = new ResourceMetadataColumn('test', ['property' => 'name', 'definition' => ['type' => 'type']]);
        $this->assertEquals('type', $column->getType());
    }

    /**
     * @test
     */
    public function isNullable()
    {
        $column = new ResourceMetadataColumn('test', ['property' => 'name', 'definition' => ['options' => ['null' => false]]]);
        $this->assertFalse($column->isNullable());
        $column = new ResourceMetadataColumn('test', ['property' => 'name', 'definition' => ['options' => ['null' => true]]]);
        $this->assertTrue($column->isNullable());
    }
}
