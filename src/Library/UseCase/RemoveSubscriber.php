<?php

declare(strict_types=1);

namespace Library\UseCase;

use Library\Contract\LibrarySubscriberFactoryInterface;
use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\RemoveSubscriberInterface;
use Library\Exception\LibrarySubscriberNotFoundException;

class RemoveSubscriber implements RemoveSubscriberInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $repository,
        private LibrarySubscriberFactoryInterface $factory
    ) {
    }

    public function remove(string $uuid): LibrarySubscriberInterface
    {
        $subscriber = $this->repository->findLibrarySubscriberByUuid($uuid);
        if (! $subscriber) {
            throw new LibrarySubscriberNotFoundException();
        }
        $subscriber = $this->factory->create($subscriber, false);
        $this->repository->unregisterLibrarySubscriber($subscriber);

        return $subscriber;
    }
}
