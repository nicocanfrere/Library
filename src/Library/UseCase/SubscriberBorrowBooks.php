<?php

declare(strict_types=1);

namespace Library\UseCase;

use Exception;
use Library\Contract\BookBorrowRegistryFactoryInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\SubscriberBorrowBooksInterface;
use Library\Exception\LibrarySubscriberNotFoundException;
use Psr\Log\LoggerInterface;

class SubscriberBorrowBooks implements SubscriberBorrowBooksInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $subscriberRepository,
        private BookRepositoryInterface $bookRepository,
        private BookBorrowRegistryFactoryInterface $bookBorrowRegistryFactory,
        private LoggerInterface $logger
    ) {
    }

    public function borrow(string $subscriberUuid, array $bookUuids): array
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
        $notExistingBooks        = [];
        $booksAlreadyBorrowed    = [];
        $booksNotAlreadyBorrowed = [];
        foreach ($bookUuids as $bookUuid) {
            $book = $this->bookRepository->findBookByUuid($bookUuid);
            if ($book) {
                try {
                    $this->bookBorrowRegistryFactory->create(
                        ['subscriber_uuid' => $subscriberUuid, 'book_uuid' => $bookUuid]
                    );
                    $booksNotAlreadyBorrowed[] = $bookUuid;
                } catch (Exception $exception) {
                    $this->logger->critical(
                        __METHOD__,
                        ['error' => $exception->getMessage()]
                    );
                    $booksAlreadyBorrowed[] = $bookUuid;
                }
            } else {
                $notExistingBooks[] = $bookUuid;
            }
        }

        return [
            self::BORROWED_BOOKS       => $booksNotAlreadyBorrowed,
            self::NOT_BORROWABLE_BOOKS => $booksAlreadyBorrowed,
            self::UNKNOWN_BOOKS        => $notExistingBooks,
        ];
    }
}
