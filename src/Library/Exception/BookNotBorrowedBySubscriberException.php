<?php

declare(strict_types=1);

namespace Library\Exception;

use Exception;

class BookNotBorrowedBySubscriberException extends Exception
{
    /** @var string */
    protected $message = 'book_borrow_registry.book_not_borrowed_by_subscriber.error';
}
