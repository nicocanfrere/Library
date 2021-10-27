<?php

declare(strict_types=1);

namespace LibraryTest;

use Library\Contract\LibrarySubscriberInterface;
use Library\LibrarySubscriber;
use PHPUnit\Framework\TestCase;

/*
 * Specs :
 * Un utilisateur contient identifiant unique (UUID) nom, prénom, email (unique).
 */
class LibrarySubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsLibrarySubscriberInterface()
    {
        $subscriber = new LibrarySubscriber();
        $this->assertInstanceOf(LibrarySubscriberInterface::class, $subscriber);
    }

    /**
     * @test
     */
    public function subscriberHasAnIdentifierPropertyNullByDefault()
    {
        $subscriber = new LibrarySubscriber();
        $this->assertNull($subscriber->getUuid());
    }

    /**
     * @test
     */
    public function subscriberHasFirstNamePropertyNullByDefault()
    {
        $subscriber = new LibrarySubscriber();
        $this->assertNull($subscriber->getFirstName());
    }

    /**
     * @test
     */
    public function subscriberHasLastNamePropertyNullByDefault()
    {
        $subscriber = new LibrarySubscriber();
        $this->assertNull($subscriber->getLastName());
    }

    /**
     * @test
     */
    public function subscriberHasEmailPropertyNullByDefault()
    {
        $subscriber = new LibrarySubscriber();
        $this->assertNull($subscriber->getEmail());
    }

    /**
     * @test
     */
    public function subscriberCreate()
    {
        $uuid      = 'aaa-bbb';
        $firstName = 'John';
        $lastName  = 'Doe';
        $email     = 'john.doe@example.org';

        $subscriber = LibrarySubscriber::create($uuid, $firstName, $lastName, $email);

        $this->assertInstanceOf(LibrarySubscriber::class, $subscriber);
        $this->assertEquals($uuid, $subscriber->getUuid());
        $this->assertEquals($firstName, $subscriber->getFirstName());
        $this->assertEquals($lastName, $subscriber->getLastName());
        $this->assertEquals($email, $subscriber->getEmail());
    }
}
