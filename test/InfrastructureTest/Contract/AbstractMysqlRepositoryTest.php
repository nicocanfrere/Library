<?php

declare(strict_types=1);

namespace InfrastructureTest\Contract;

use Infrastructure\Contract\AbstractRepository;
use Infrastructure\Contract\DatabaseConnectionInterface;
use PHPUnit\Framework\TestCase;

class AbstractMysqlRepositoryTest extends TestCase
{
    private AbstractRepository $testable;

    protected function setUp(): void
    {
        $connection     = $this->createMock(DatabaseConnectionInterface::class);
        $this->testable = new TestableMysqlRepository($connection);
    }

    /**
     * @test
     */
    public function generateInsertSqlQuery()
    {
        $sql      = $this->testable->generateInsertSqlQuery(
            [
                'table'   => 'books',
                'primary' => 'uuid',
                'columns' => [
                    'uuid'                => ['property' => 'uuid'],
                    'title'               => ['property' => 'title'],
                    'author_name'         => ['property' => 'authorName'],
                    'year_of_publication' => ['property' => 'yearOfPublication'],
                ],
            ]
        );
        $expected = 'INSERT INTO books (uuid, title, author_name, year_of_publication) VALUES (:uuid, :title, :author_name, :year_of_publication)';
        $this->assertEquals($expected, $sql);
    }

    /**
     * @test
     */
    public function generateUpdateSqlQuery()
    {
        $sql      = $this->testable->generateUpdateSqlQuery(
            [
                'table'   => 'books',
                'primary' => 'uuid',
                'columns' => [
                    'uuid'                => ['property' => 'uuid'],
                    'title'               => ['property' => 'title'],
                    'author_name'         => ['property' => 'authorName'],
                    'year_of_publication' => ['property' => 'yearOfPublication'],
                ],
            ]
        );
        $expected = 'UPDATE books SET title = :title, author_name = :author_name, year_of_publication = :year_of_publication WHERE uuid = :uuid';
        $this->assertEquals($expected, $sql);
    }

    /**
     * @test
     */
    public function generateOrderByPart()
    {
        $orderBy  = $this->testable->generateOrderByPart(
            [
                'table'   => 'books',
                'primary' => 'uuid',
                'columns' => [
                    'uuid'                => ['property' => 'uuid'],
                    'title'               => ['property' => 'title'],
                    'author_name'         => ['property' => 'authorName'],
                    'year_of_publication' => ['property' => 'yearOfPublication'],
                ],
            ],
            [
                'title'       => 'asc',
                'author_name' => 'desc',
            ]
        );
        $expected = 'ORDER BY title ASC, author_name DESC';
        $this->assertEquals($expected, $orderBy);
    }
}
