<?php

declare(strict_types=1);

namespace Library\Exception;

use Exception;

class BookNotFoundInRegistryException extends Exception
{
    protected $message = 'book_borrow_registry.book_not_found.error';
}
