<?php

declare(strict_types=1);

namespace LibraryTest\Specification;

use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Specification\EmailIsAvailableSpecification;
use PHPUnit\Framework\TestCase;

class EmailIsAvailableSpecificationTest extends TestCase
{
    private LibrarySubscriberRepositoryInterface $subscriberRepository;
    private EmailIsAvailableSpecification $specification;

    protected function setUp(): void
    {
        $this->subscriberRepository = $this->createMock(LibrarySubscriberRepositoryInterface::class);
        $this->specification        = new EmailIsAvailableSpecification($this->subscriberRepository);
    }

    /**
     * @test
     */
    public function emailIsAvailableOnCreateNewSubscriber()
    {
        $this->subscriberRepository->method('findOneByEmail')->willReturn(null);
        $satisfied = $this->specification->isSatisfiedBy(['email' => 'john.doe@exapmle.com']);

        $this->assertTrue($satisfied);
    }

    /**
     * @test
     */
    public function emailIsAvailableOnUpdateNewSubscriber()
    {
        $found             = ['uuid' => 'abc', 'email' => 'john.doe@exapmle.com'];
        $updatedSubscriber = ['uuid' => 'abc', 'email' => 'john.doe@exapmle.com'];
        $this->subscriberRepository->method('findOneByEmail')->willReturn($found);
        $satisfied = $this->specification->isSatisfiedBy($updatedSubscriber);

        $this->assertTrue($satisfied);
    }

    /**
     * @test
     */
    public function emailIsNotAvailableOnCreateNewSubscriber()
    {
        $found         = ['uuid' => 'abc', 'email' => 'john.doe@exapmle.com'];
        $newSubscriber = ['email' => 'john.doe@exapmle.com'];
        $this->subscriberRepository->method('findOneByEmail')->willReturn($found);
        $satisfied = $this->specification->isSatisfiedBy($newSubscriber);

        $this->assertFalse($satisfied);
    }

    /**
     * @test
     */
    public function emailIsNotAvailableOnUpdateNewSubscriber()
    {
        $found             = ['uuid' => 'abc', 'email' => 'john.doe@exapmle.com'];
        $updatedSubscriber = ['uuid' => 'def', 'email' => 'john.doe@exapmle.com'];
        $this->subscriberRepository->method('findOneByEmail')->willReturn($found);
        $satisfied = $this->specification->isSatisfiedBy($updatedSubscriber);

        $this->assertFalse($satisfied);
    }
}
