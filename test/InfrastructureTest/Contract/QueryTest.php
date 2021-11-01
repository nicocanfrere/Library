<?php

declare(strict_types=1);

namespace InfrastructureTest\Contract;

use Infrastructure\Contract\DatabaseConnectionInterface;
use Infrastructure\Contract\QueryInterface;
use Infrastructure\Database\Query;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class QueryTest extends TestCase
{
    private QueryInterface $query;

    protected function setUp(): void
    {
        $connection  = $this->createMock(DatabaseConnectionInterface::class);
        $logger      = $this->createMock(LoggerInterface::class);
        $this->query = new Query($connection, $logger);
    }

    /**
     * @test
     */
    public function generateInsertSqlQuery()
    {
        $sql      = $this->query->generateInsertSqlQuery(
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
        $sql      = $this->query->generateUpdateSqlQuery(
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
        $orderBy  = $this->query->generateOrderByPart(
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
