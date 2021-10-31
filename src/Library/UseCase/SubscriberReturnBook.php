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

class SubscriberReturnBook implements SubscriberReturnBookInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $subscriberRepository,
        private BookBorrowRegistryRepositoryInterface $bookBorrowRegistryRepository
    ) {
    }

    public function returnBook(string $subscriberUuid, string $bookUuid): array
    {
        $subscriber = $this->subscriberRepository->findLibrarySubscriberByUuid($subscriberUuid);
        if (! $subscriber) {
            throw new LibrarySubscriberNotFoundException();
        }
        $registry = $this->bookBorrowRegistryRepository->findOneByBookUuid($bookUuid);
        if (! $registry) {
            throw new BookNotFoundInRegistryException();
        }
        if ($registry['subscriber'] !== $subscriber['uuid']) {
            throw new BookNotBorrowedBySubscriberException();
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
