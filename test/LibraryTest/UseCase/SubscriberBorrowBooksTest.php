<?php

declare(strict_types=1);

namespace LibraryTest\UseCase;

use Library\BookBorrowRegistryFactory;
use Library\Contract\BookBorrowRegistryFactoryInterface;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\IdentifierFactoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\SubscriberBorrowBooksInterface;
use Library\Exception\LibrarySubscriberNotFoundException;
use Library\UseCase\SubscriberBorrowBooks;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SubscriberBorrowBooksTest extends TestCase
{
    private LibrarySubscriberRepositoryInterface $subscriberRepository;
    private BookRepositoryInterface $bookRepository;
    private BookBorrowRegistryFactoryInterface $registryFactory;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->subscriberRepository = $this->createMock(LibrarySubscriberRepositoryInterface::class);
        $this->bookRepository       = $this->createMock(BookRepositoryInterface::class);
        $this->registryFactory      = $this->createMock(BookBorrowRegistryFactoryInterface::class);
        $this->logger               = $this->createMock(LoggerInterface::class);
    }

    /**
     * @test
     */
    public function throwExceptionOnSubscriberNotExists()
    {
        $this->subscriberRepository->method('findLibrarySubscriberByUuid')->willReturn(null);
        $subscriberBorrowBooks = new SubscriberBorrowBooks(
            $this->subscriberRepository,
            $this->bookRepository,
            $this->registryFactory,
            $this->logger
        );
        $this->expectException(LibrarySubscriberNotFoundException::class);
        $subscriberBorrowBooks->borrow('subscriber-uuid', []);
    }

    /**
     * @test
     */
    public function bookCanBeBorrowed()
    {
        $this->subscriberRepository->method('findLibrarySubscriberByUuid')->willReturn(['uuid']);
        $this->bookRepository->method('findBookByUuid')->willReturn([true]);
        $subscriberBorrowBooks = new SubscriberBorrowBooks(
            $this->subscriberRepository,
            $this->bookRepository,
            $this->registryFactory,
            $this->logger
        );
        $result                = $subscriberBorrowBooks->borrow('subscriber-uuid', ['book-uuid']);
        $this->assertArrayHasKey(SubscriberBorrowBooksInterface::BORROWED_BOOKS, $result);
        $this->assertCount(1, $result[SubscriberBorrowBooksInterface::BORROWED_BOOKS]);
        $this->assertEquals('book-uuid', $result[SubscriberBorrowBooksInterface::BORROWED_BOOKS][0]);
    }

    /**
     * @test
     */
    public function bookCanNotBeBorrowed()
    {
        $this->subscriberRepository->method('findLibrarySubscriberByUuid')->willReturn(['uuid']);
        $this->bookRepository->method('findBookByUuid')->willReturn([true]);
        $bookBorrowRegistryRepository = $this->createMock(BookBorrowRegistryRepositoryInterface::class);
        $bookBorrowRegistryRepository->method('bookCanBeBorrowed')->willReturn(false);
        $bookBorrowRegistryFactory = new BookBorrowRegistryFactory(
            $bookBorrowRegistryRepository,
            $this->createMock(IdentifierFactoryInterface::class),
            $this->logger
        );
        $subscriberBorrowBooks     = new SubscriberBorrowBooks(
            $this->subscriberRepository,
            $this->bookRepository,
            $bookBorrowRegistryFactory,
            $this->logger
        );
        $result                    = $subscriberBorrowBooks->borrow('subscriber-uuid', ['book-uuid']);
        $this->assertArrayHasKey(SubscriberBorrowBooksInterface::NOT_BORROWABLE_BOOKS, $result);
        $this->assertCount(1, $result[SubscriberBorrowBooksInterface::NOT_BORROWABLE_BOOKS]);
        $this->assertEquals('book-uuid', $result[SubscriberBorrowBooksInterface::NOT_BORROWABLE_BOOKS][0]);
    }

    /**
     * @test
     */
    public function unknownBook()
    {
        $this->subscriberRepository->method('findLibrarySubscriberByUuid')->willReturn(['uuid']);
        $this->bookRepository->method('findBookByUuid')->willReturn(null);
        $subscriberBorrowBooks = new SubscriberBorrowBooks(
            $this->subscriberRepository,
            $this->bookRepository,
            $this->registryFactory,
            $this->logger
        );
        $result                = $subscriberBorrowBooks->borrow('subscriber-uuid', ['book-uuid']);
        $this->assertArrayHasKey(SubscriberBorrowBooksInterface::UNKNOWN_BOOKS, $result);
        $this->assertCount(1, $result[SubscriberBorrowBooksInterface::UNKNOWN_BOOKS]);
        $this->assertEquals('book-uuid', $result[SubscriberBorrowBooksInterface::UNKNOWN_BOOKS][0]);
    }
}
