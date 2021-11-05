<?php

declare(strict_types=1);

namespace LibraryTest\UseCase;

use Library\Contract\IdentifierFactoryInterface;
use Library\Contract\LibrarySubscriberFactoryInterface;
use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Exception\LibrarySubscriberNotFoundException;
use Library\LibrarySubscriberFactory;
use Library\Specification\EmailIsAvailableSpecification;
use Library\UseCase\RemoveSubscriber;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class RemoveSubscriberTest extends TestCase
{
    private LibrarySubscriberRepositoryInterface $repository;
    private LibrarySubscriberFactoryInterface $factory;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(LibrarySubscriberRepositoryInterface::class);
        $this->factory    = $this->createMock(LibrarySubscriberFactoryInterface::class);
        $this->logger     = $this->createMock(LoggerInterface::class);
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
            $this->factory,
            $this->logger
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
        $identifierFactory = $this->createMock(IdentifierFactoryInterface::class);
        $identifierFactory->method('create')->willReturn('uuid');
        $specification = $this->createMock(EmailIsAvailableSpecification::class);
        $removeSubscriber = new RemoveSubscriber(
            $this->repository,
            new LibrarySubscriberFactory($this->repository, $identifierFactory, $specification, $this->logger),
            $this->logger
        );
        $subscriber       = $removeSubscriber->remove('uuid');
        $this->assertInstanceOf(LibrarySubscriberInterface::class, $subscriber);
    }
}
