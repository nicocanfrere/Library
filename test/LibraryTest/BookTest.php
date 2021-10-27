<?php

declare(strict_types=1);

namespace LibraryTest;

use Library\Book;
use Library\Contract\BookInterface;
use PHPUnit\Framework\TestCase;

/*
 * Specs :
 * Un livre contient un identifiant unique (UUID), un titre, un nom d'auteur et une année.
 */
class BookTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsBookInterface()
    {
        $book = new Book();
        $this->assertInstanceOf(BookInterface::class, $book);
    }

    /**
     * @test
     */
    public function bookHasAnIdentifierPropertyNullByDefault()
    {
        $book = new Book();
        $this->assertNull($book->getUuid());
    }

    /**
     * @test
     */
    public function bookHasATitlePropertyNullByDefault()
    {
        $book = new Book();
        $this->assertNull($book->getTitle());
    }

    /**
     * @test
     */
    public function bookHasAnAuthorNamePropertyNullByDefault()
    {
        $book = new Book();
        $this->assertNull($book->getAuthorName());
    }

    /**
     * @test
     */
    public function bookHasAYearOfPublicationPropertyNullByDefault()
    {
        $book = new Book();
        $this->assertNull($book->getAuthorName());
    }

    /**
     * @test
     */
    public function bookCreate()
    {
        $uuid              = 'aaa-bbb';
        $title             = 'The Faceless Man';
        $authorName        = 'John Doe';
        $yearOfPublication = 2021;

        $book = Book::create($uuid, $title, $authorName, $yearOfPublication);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($uuid, $book->getUuid());
        $this->assertEquals($title, $book->getTitle());
        $this->assertEquals($authorName, $book->getAuthorName());
        $this->assertEquals($yearOfPublication, $book->getYearOfPublication());
    }
}
