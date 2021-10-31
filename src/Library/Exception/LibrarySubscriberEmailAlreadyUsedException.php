<?php

declare(strict_types=1);

namespace Library\Exception;

use Exception;

class LibrarySubscriberEmailAlreadyUsedException extends Exception
{
    /** @var string */
    protected $message = 'library_subscriber.email_already_used.error';
    /** @var int */
    protected $code = 422;
}
