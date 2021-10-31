<?php

declare(strict_types=1);

namespace LibraryTest\UseCase;

use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\SubscriberBorrowBooksInterface;
use Library\Exception\LibrarySubscriberNotFoundException;
use Library\UseCase\SubscriberBorrowBooks;
use PHPUnit\Framework\TestCase;

class SubscriberBorrowBooksTest extends TestCase
{
    private LibrarySubscriberRepositoryInterface $subscriberRepository;
    private BookRepositoryInterface $bookRepository;
    private BookBorrowRegistryRepositoryInterface $registryRepository;

    protected function setUp(): void
    {
        $this->subscriberRepository = $this->createMock(LibrarySubscriberRepositoryInterface::class);
        $this->bookRepository       = $this->createMock(BookRepositoryInterface::class);
        $this->registryRepository   = $this->createMock(BookBorrowRegistryRepositoryInterface::class);
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
            $this->registryRepository
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
        $this->registryRepository->method('bookCanBeBorrowed')->willReturn(true);
        $subscriberBorrowBooks = new SubscriberBorrowBooks(
            $this->subscriberRepository,
            $this->bookRepository,
            $this->registryRepository
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
        $this->registryRepository->method('bookCanBeBorrowed')->willReturn(false);
        $subscriberBorrowBooks = new SubscriberBorrowBooks(
            $this->subscriberRepository,
            $this->bookRepository,
            $this->registryRepository
        );
        $result                = $subscriberBorrowBooks->borrow('subscriber-uuid', ['book-uuid']);
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
            $this->registryRepository
        );
        $result                = $subscriberBorrowBooks->borrow('subscriber-uuid', ['book-uuid']);
        $this->assertArrayHasKey(SubscriberBorrowBooksInterface::UNKNOWN_BOOKS, $result);
        $this->assertCount(1, $result[SubscriberBorrowBooksInterface::UNKNOWN_BOOKS]);
        $this->assertEquals('book-uuid', $result[SubscriberBorrowBooksInterface::UNKNOWN_BOOKS][0]);
    }
}
