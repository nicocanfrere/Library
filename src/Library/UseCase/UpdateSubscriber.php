<?php

declare(strict_types=1);

namespace Library\UseCase;

use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\UpdateSubscriberInterface;
use Library\Exception\LibrarySubscriberEmailAlreadyUsedException;
use Library\Exception\LibrarySubscriberNotFoundException;
use Library\LibrarySubscriber;
use Psr\Log\LoggerInterface;

use function array_filter;
use function array_key_exists;
use function array_merge;

class UpdateSubscriber implements UpdateSubscriberInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $repository,
        private LoggerInterface $logger
    ) {
    }

    public function update(string $uuid, array $data): LibrarySubscriberInterface
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
        if (
            array_key_exists('email', $data)
            && $data['email'] !== $subscriber['email']
        ) {
            $exists = $this->repository->findOneByEmail($data['email']);
            if ($exists && $exists['uuid'] !== $subscriber['uuid']) {
                $exception = new LibrarySubscriberEmailAlreadyUsedException();
                $this->logger->critical(
                    __METHOD__,
                    ['error' => $exception->getMessage()]
                );
                throw $exception;
            }
        }
        unset($data['uuid']);
        $filtered   = array_filter($data);
        $subscriber = array_merge($subscriber, $filtered);
        $subscriber = LibrarySubscriber::create(
            $subscriber['uuid'],
            $subscriber['first_name'],
            $subscriber['last_name'],
            $subscriber['email']
        );
        $this->repository->updateSubscriber($subscriber);

        return $subscriber;
    }
}
