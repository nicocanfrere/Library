<?php

declare(strict_types=1);

namespace Library;

use Library\Contract\IdentifierFactoryInterface;
use Library\Contract\LibrarySubscriberFactoryInterface;
use Library\Contract\LibrarySubscriberInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Exception\LibrarySubscriberEmailAlreadyUsedException;
use Psr\Log\LoggerInterface;

class LibrarySubscriberFactory implements LibrarySubscriberFactoryInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $repository,
        private IdentifierFactoryInterface $identifierFactory,
        private LoggerInterface $logger
    ) {
    }

    public function create(array $data, ?bool $save = true): LibrarySubscriberInterface
    {
        if ($save) {
            $exists = $this->repository->findOneByEmail($data['email']);
            if (! empty($exists)) {
                $exception = new LibrarySubscriberEmailAlreadyUsedException();
                $this->logger->critical(
                    __METHOD__,
                    ['error' => $exception->getMessage()]
                );
                throw $exception;
            }
        }
        $uuid       = ! empty($data['uuid']) ? $data['uuid'] : $this->identifierFactory->create();
        $subscriber = LibrarySubscriber::create(
            $uuid,
            $data['first_name'],
            $data['last_name'],
            $data['email']
        );
        if (empty($data['uuid']) && $save) {
            $this->repository->registerNewLibrarySubscriber($subscriber);
        }

        return $subscriber;
    }
}
