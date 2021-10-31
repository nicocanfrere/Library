<?php

declare(strict_types=1);

namespace Library\UseCase;

use Library\BookBorrowRegistry;
use Library\Contract\BookBorrowRegistryRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\SubscriberReturnBookInterface;
use Library\Exception\BookNotBorrowedBySubscriberException;
use Library\Exception\BookNotFoundInRegistryException;
use Library\Exception\LibrarySubscriberNotFoundException;
use Psr\Log\LoggerInterface;

class SubscriberReturnBook implements SubscriberReturnBookInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $subscriberRepository,
        private BookBorrowRegistryRepositoryInterface $bookBorrowRegistryRepository,
        private LoggerInterface $logger
    ) {
    }

    public function returnBook(string $subscriberUuid, string $bookUuid): array
    {
        $subscriber = $this->subscriberRepository->findLibrarySubscriberByUuid($subscriberUuid);
        if (! $subscriber) {
            $exception = new LibrarySubscriberNotFoundException();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            throw $exception;
        }
        $registry = $this->bookBorrowRegistryRepository->findOneByBookUuid($bookUuid);
        if (! $registry) {
            $exception = new BookNotFoundInRegistryException();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            throw $exception;
        }
        if ($registry['subscriber'] !== $subscriber['uuid']) {
            $exception = new BookNotBorrowedBySubscriberException();
            $this->logger->critical(
                __METHOD__,
                ['error' => $exception->getMessage()]
            );
            throw $exception;
        }
        $registry = BookBorrowRegistry::create(
            $registry['uuid'],
            $registry['subscriber'],
            $registry['book']
        );
        $this->bookBorrowRegistryRepository->returnABook($registry);

        return [
            'subscriber_uuid' => $subscriberUuid,
            'book_uuid'       => $bookUuid,
        ];
    }
}
