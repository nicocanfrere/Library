<?php

declare(strict_types=1);

namespace Library\UseCase;

use Exception;
use Library\Contract\BookBorrowRegistryFactoryInterface;
use Library\Contract\BookRepositoryInterface;
use Library\Contract\LibrarySubscriberRepositoryInterface;
use Library\Contract\SubscriberBorrowBooksInterface;
use Library\Exception\LibrarySubscriberNotFoundException;

class SubscriberBorrowBooks implements SubscriberBorrowBooksInterface
{
    public function __construct(
        private LibrarySubscriberRepositoryInterface $subscriberRepository,
        private BookRepositoryInterface $bookRepository,
        private BookBorrowRegistryFactoryInterface $bookBorrowRegistryFactory
    ) {
    }

    public function borrow(string $subscriberUuid, array $bookUuids): array
    {
        $subscriber = $this->subscriberRepository->findLibrarySubscriberByUuid($subscriberUuid);
        if (! $subscriber) {
            throw new LibrarySubscriberNotFoundException();
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
                    //var_dump($exception->getMessage());die;
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
