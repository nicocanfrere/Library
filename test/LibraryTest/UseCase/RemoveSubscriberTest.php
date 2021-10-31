<?php

declare(strict_types=1);

namespace LibraryTest\UseCase;

use Library\Contract\LibrarySubscriberFactoryInterface;
use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Exception\LibrarySubscriberNotFoundException;
use Library\LibrarySubscriberFactory;
use Library\UseCase\RemoveSubscriber;
use PHPUnit\Framework\TestCase;

class RemoveSubscriberTest extends TestCase
{
    private LibrarySubscriberRepositoryInterface $repository;
    private LibrarySubscriberFactoryInterface $factory;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(LibrarySubscriberRepositoryInterface::class);
        $this->factory    = $this->createMock(LibrarySubscriberFactoryInterface::class);
    }

    /**
     * @test
     */
    public function throwExceptionOnLibrarySubscriberNotExists()
    {
        $this->repository->method('findLibrarySubscriberByUuid')->willReturn(null);
        $this->expectException(LibrarySubscriberNotFoundException::class);
        $removeSubscriber = new RemoveSubscriber(
            $this->repository,
            $this->factory
        );
        $removeSubscriber->remove('uuid');
    }

    /**
     * @test
     */
    public function removeExistingSubscriber()
    {
        $values =
            [
                'uuid'       => 'uuid',
                'first_name' => 'new_first_name',
                'last_name'  => 'new_last_name',
                'email'      => 'new_email@example.com',
            ];
        $this->repository->method('findLibrarySubscriberByUuid')->willReturn($values);
        $removeSubscriber = new RemoveSubscriber(
            $this->repository,
            new LibrarySubscriberFactory($this->repository)
        );
        $subscriber       = $removeSubscriber->remove('uuid');
        $this->assertInstanceOf(LibrarySubscriberInterface::class, $subscriber);
    }
}
