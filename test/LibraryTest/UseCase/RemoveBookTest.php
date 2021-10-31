<?php

declare(strict_types=1);

namespace LibraryTest\UseCase;

use Library\BookFactory;
use Library\Contract\BookFactoryInterface;
use Library\Contract\BookInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Exception\BookNotFoundException;
use Library\UseCase\RemoveBook;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class RemoveBookTest extends TestCase
{
    private BookRepositoryInterface $bookRepository;
    private BookFactoryInterface $bookFactory;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->bookFactory    = $this->createMock(BookFactoryInterface::class);
        $this->logger         = $this->createMock(LoggerInterface::class);
    }

    /**
     * @test
     */
    public function throwExceptionOnBookNotExists()
    {
        $this->bookRepository->method('findBookByUuid')->willReturn(null);
        $this->expectException(BookNotFoundException::class);
        $removeBook = new RemoveBook($this->bookRepository, $this->bookFactory, $this->logger);
        $removeBook->remove('uuid');
    }

    /**
     * @test
     */
    public function removeExistingBook()
    {
        $values = ['uuid' => 'uuid', 'title' => 'title', 'author_name' => 'author_name', 'year_of_publication' => 2021];
        $this->bookRepository->method('findBookByUuid')->willReturn($values);
        $remover = new RemoveBook(
            $this->bookRepository,
            new BookFactory($this->bookRepository),
            $this->logger
        );
        $book    = $remover->remove('uuid');
        $this->assertInstanceOf(BookInterface::class, $book);
    }
}
