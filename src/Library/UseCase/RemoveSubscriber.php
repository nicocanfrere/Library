<?php

declare(strict_types=1);

namespace Library\UseCase;

use Library\Contract\LibrarySubscriberFactoryInterface;
use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\RemoveSubscriberInterface;
use Library\Exception\LibrarySubscriberNotFoundException;
use Psr\Log\LoggerInterface;

class RemoveSubscriber implements RemoveSubscriberInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $repository,
        private LibrarySubscriberFactoryInterface $factory,
        private LoggerInterface $logger
    ) {
    }

    public function remove(string $uuid): LibrarySubscriberInterface
    {
        $subscriber = $this->repository->findLibrarySubscriberByUuid($uuid);
        if (! $subscriber) {
            $exception = new LibrarySubscriberNotFoundException();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            throw $exception;
        }
        $subscriber = $this->factory->create($subscriber, false);
        $this->repository->unregisterLibrarySubscriber($subscriber);

        return $subscriber;
    }
}
