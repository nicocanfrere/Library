<?php

declare(strict_types=1);

namespace LibraryTest\UseCase;

use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Exception\LibrarySubscriberEmailAlreadyUsedException;
use Library\Exception\LibrarySubscriberNotFoundException;
use Library\UseCase\UpdateSubscriber;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class UpdateSubscriberTest extends TestCase
{
    private LibrarySubscriberRepositoryInterface $subscriberRepository;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->subscriberRepository = $this->createMock(LibrarySubscriberRepositoryInterface::class);
        $this->logger               = $this->createMock(LoggerInterface::class);
    }

    /**
     * @test
     */
    public function throwExceptionOnLibrarySubscriberNotFound()
    {
        $values = ['uuid' => 'uuid', 'first_name' => '', 'last_name' => '', 'email' => 'email@example.com'];
        $this->subscriberRepository->method('findLibrarySubscriberByUuid')->willReturn(null);
        $updateSubscriber = new UpdateSubscriber($this->subscriberRepository, $this->logger);
        $this->expectException(LibrarySubscriberNotFoundException::class);
        $updatedSubscriber = $updateSubscriber->update(
            'uuid',
            $values
        );
    }

    /**
     * @test
     */
    public function throwExceptionOnLibrarySubscriberWithAlreadyUsedEmail()
    {
        $values = ['uuid' => 'uuid', 'first_name' => '', 'last_name' => '', 'email' => 'updated_email@example.com'];
        $this
            ->subscriberRepository
            ->method('findLibrarySubscriberByUuid')->willReturn(['uuid' => 'uuid', 'email' => 'email@example.com']);
        $this
            ->subscriberRepository
            ->method('findOneByEmail')->willReturn(['uuid' => 'other', 'email' => 'updated_email@example.com']);
        $updateSubscriber = new UpdateSubscriber($this->subscriberRepository, $this->logger);
        $this->expectException(LibrarySubscriberEmailAlreadyUsedException::class);
        $updatedSubscriber = $updateSubscriber->update(
            'uuid',
            $values
        );
    }

    /**
     * @test
     */
    public function updateAllFieldsInExistingLibrarySubscriber()
    {
        $subscriber =
            [
                'uuid'       => 'uuid',
                'first_name' => 'first_name',
                'last_name'  => 'last_name',
                'email'      => 'email@example.com',
            ];
        $values     =
            [
                'uuid'       => 'uuid',
                'first_name' => 'new_first_name',
                'last_name'  => 'new_last_name',
                'email'      => 'new_email@example.com',
            ];
        $this->subscriberRepository->method('findLibrarySubscriberByUuid')->willReturn($subscriber);
        $updateSubscriber  = new UpdateSubscriber($this->subscriberRepository, $this->logger);
        $updatedSubscriber = $updateSubscriber->update(
            'uuid',
            $values
        );
        $this->assertInstanceOf(LibrarySubscriberInterface::class, $updatedSubscriber);
        $this->assertEquals($values['uuid'], $updatedSubscriber->getUuid());
        $this->assertEquals($values['first_name'], $updatedSubscriber->getFirstName());
        $this->assertEquals($values['last_name'], $updatedSubscriber->getLastName());
        $this->assertEquals($values['email'], $updatedSubscriber->getEmail());
    }

    /**
     * @test
     */
    public function updateSomeFieldsInExistingLibrarySubscriber()
    {
        $subscriber = [
            'uuid'       => 'uuid',
            'first_name' => 'first_name',
            'last_name'  => 'last_name',
            'email'      => 'email@example.com',
        ];
        $values     = ['uuid' => 'uuid', 'first_name' => '', 'email' => 'new_email@example.com'];
        $this->subscriberRepository->method('findLibrarySubscriberByUuid')->willReturn($subscriber);
        $this->subscriberRepository->method('findOneByEmail')->willReturn(null);
        $updateSubscriber  = new UpdateSubscriber($this->subscriberRepository, $this->logger);
        $updatedSubscriber = $updateSubscriber->update(
            'uuid',
            $values
        );
        $this->assertInstanceOf(LibrarySubscriberInterface::class, $updatedSubscriber);
        $this->assertEquals($values['uuid'], $updatedSubscriber->getUuid());
        $this->assertEquals($subscriber['first_name'], $updatedSubscriber->getFirstName());
        $this->assertEquals($subscriber['last_name'], $updatedSubscriber->getLastName());
        $this->assertEquals($values['email'], $updatedSubscriber->getEmail());
    }
}
