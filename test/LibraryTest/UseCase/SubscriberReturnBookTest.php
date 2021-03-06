<?php

declare(strict_types=1);

namespace LibraryTest\UseCase;

use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Exception\BookNotBorrowedBySubscriberException;
use Library\Exception\BookNotFoundInRegistryException;
use Library\Exception\LibrarySubscriberNotFoundException;
use Library\UseCase\SubscriberReturnBook;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SubscriberReturnBookTest extends TestCase
{
    private LibrarySubscriberRepositoryInterface $subscriberRepository;
    private BookBorrowRegistryRepositoryInterface $bookBorrowRegistryRepository;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->subscriberRepository         = $this->createMock(LibrarySubscriberRepositoryInterface::class);
        $this->bookBorrowRegistryRepository = $this->createMock(BookBorrowRegistryRepositoryInterface::class);
        $this->logger                       = $this->createMock(LoggerInterface::class);
    }

    /**
     * @test
     */
    public function throwExceptionOnSubscriberNotExists()
    {
        $this->subscriberRepository->method('findLibrarySubscriberByUuid')->willReturn([]);
        $returnBook = new SubscriberReturnBook(
            $this->subscriberRepository,
            $this->bookBorrowRegistryRepository,
            $this->logger
        );
        $this->expectException(LibrarySubscriberNotFoundException::class);
        $returnBook->returnBook('subscriber-uuid', 'book-uuid');
    }

    /**
     * @test
     */
    public function throwExceptionOnBookNotFoundInRegistry()
    {
        $this->subscriberRepository
            ->method('findLibrarySubscriberByUuid')
            ->willReturn(['subscriber-uuid']);
        $this->bookBorrowRegistryRepository
            ->method('findOneByBookUuid')
            ->willReturn([]);
        $returnBook = new SubscriberReturnBook(
            $this->subscriberRepository,
            $this->bookBorrowRegistryRepository,
            $this->logger
        );
        $this->expectException(BookNotFoundInRegistryException::class);
        $returnBook->returnBook('subscriber-uuid', 'book-uuid');
    }

    /**
     * @test
     */
    public function throwExceptionOnBookNotBorrowedBySubscriber()
    {
        $this->subscriberRepository
            ->method('findLibrarySubscriberByUuid')
            ->willReturn(['uuid' => 'subscriber-uuid']);
        $this->bookBorrowRegistryRepository
            ->method('findOneByBookUuid')
            ->willReturn(['subscriber' => 'another-subscriber-uuid']);
        $returnBook = new SubscriberReturnBook(
            $this->subscriberRepository,
            $this->bookBorrowRegistryRepository,
            $this->logger
        );
        $this->expectException(BookNotBorrowedBySubscriberException::class);
        $returnBook->returnBook('subscriber-uuid', 'book-uuid');
    }

    /**
     * @test
     */
    public function returnBook()
    {
        $this->subscriberRepository
            ->method('findLibrarySubscriberByUuid')
            ->willReturn(['uuid' => 'subscriber-uuid']);
        $this->bookBorrowRegistryRepository
            ->method('findOneByBookUuid')
            ->willReturn(['uuid' => 'registry-uuid', 'subscriber' => 'subscriber-uuid', 'book' => 'book-uuid']);
        $returnBook = new SubscriberReturnBook(
            $this->subscriberRepository,
            $this->bookBorrowRegistryRepository,
            $this->logger
        );
        $result     = $returnBook->returnBook('subscriber-uuid', 'book-uuid');
        $this->assertIsArray($result);
        $this->assertEquals('subscriber-uuid', $result['subscriber_uuid']);
        $this->assertEquals('book-uuid', $result['book_uuid']);
    }
}
