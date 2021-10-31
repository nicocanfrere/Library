<?php

declare(strict_types=1);

namespace LibraryTest;

use Library\BookFactory;
use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;
use PHPUnit\Framework\TestCase;

class BookFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $factory = new BookFactory(
            $this->createMock(BookRepositoryInterface::class)
        );
        $values  = ['title' => 'title', 'author_name' => 'author_name', 'year_of_publication' => 2021];
        $book    = $factory->create($values);

        $this->assertInstanceOf(BookInterface::class, $book);
        $this->assertNotNull($book->getUuid());
        $this->assertNotNull($book->getTitle());
        $this->assertNotNull($book->getAuthorName());
        $this->assertNotNull($book->getYearOfPublication());
    }
}
