<?php

declare(strict_types=1);

namespace LibraryTest\UseCase;

use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Exception\BookNotFoundException;
use Library\UseCase\UpdateBook;
use PHPUnit\Framework\TestCase;

class UpdateBookTest extends TestCase
{
    private BookRepositoryInterface $bookRepository;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
    }

    /**
     * @test
     */
    public function updateAllFieldsInExistingBook()
    {
        $book   = ['uuid' => 'uuid', 'title' => 'title', 'author_name' => 'author_name', 'year_of_publication' => 2021];
        $values = [
            'uuid'                => 'uuid',
            'title'               => 'new_title',
            'author_name'         => 'new_author_name',
            'year_of_publication' => 2020,
        ];
        $this->bookRepository->method('findBookByUuid')->willReturn($book);
        $bookUpdater = new UpdateBook($this->bookRepository);
        $updatedBook = $bookUpdater->update(
            'uuid',
            $values
        );
        $this->assertInstanceOf(BookInterface::class, $updatedBook);
        $this->assertEquals($values['uuid'], $updatedBook->getUuid());
        $this->assertEquals($values['title'], $updatedBook->getTitle());
        $this->assertEquals($values['author_name'], $updatedBook->getAuthorName());
        $this->assertEquals($values['year_of_publication'], $updatedBook->getYearOfPublication());
    }

    /**
     * @test
     */
    public function updateSomeFieldsInExistingBook()
    {
        $book   = ['uuid' => 'uuid', 'title' => 'title', 'author_name' => 'author_name', 'year_of_publication' => 2021];
        $values = ['uuid' => 'uuid', 'title' => '', 'author_name' => '', 'year_of_publication' => 2020];
        $this->bookRepository->method('findBookByUuid')->willReturn($book);
        $bookUpdater = new UpdateBook($this->bookRepository);
        $updatedBook = $bookUpdater->update(
            'uuid',
            $values
        );
        $this->assertInstanceOf(BookInterface::class, $updatedBook);
        $this->assertEquals($values['uuid'], $updatedBook->getUuid());
        $this->assertEquals($book['title'], $updatedBook->getTitle());
        $this->assertEquals($book['author_name'], $updatedBook->getAuthorName());
        $this->assertEquals($values['year_of_publication'], $updatedBook->getYearOfPublication());
    }

    /**
     * @test
     */
    public function throwExceptionOnBookNotFound()
    {
        $values = ['uuid' => 'uuid', 'title' => '', 'author_name' => '', 'year_of_publication' => 2020];
        $this->bookRepository->method('findBookByUuid')->willReturn(null);
        $bookUpdater = new UpdateBook($this->bookRepository);
        $this->expectException(BookNotFoundException::class);
        $updatedBook = $bookUpdater->update(
            'uuid',
            $values
        );
    }
}
