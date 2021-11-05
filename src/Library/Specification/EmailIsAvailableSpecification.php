<?php

declare(strict_types=1);

namespace Library\Specification;

use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\SpecificationInterface;

class EmailIsAvailableSpecification implements SpecificationInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $repository
    ) {
    }

    /**
     * @param array $subscriber
     */
    public function isSatisfiedBy($subscriber): bool
    {
        $result = $this->repository->findOneByEmail($subscriber['email']);
        $uuid   = ! empty($subscriber['uuid']) ? $subscriber['uuid'] : null;
        if ($result && $result['uuid'] !== $uuid) {
            return false;
        }

        return true;
    }
}
