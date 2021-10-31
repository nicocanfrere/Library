<?php

declare(strict_types=1);

namespace Library\Exception;

use Exception;

class BookNotFoundException extends Exception
{
    /** @var int */
    protected $code = 404;
    /** @var string */
    protected $message = 'book.not_found_error';
}
